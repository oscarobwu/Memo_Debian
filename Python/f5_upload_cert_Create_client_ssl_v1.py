#!/usr/bin/env python3
# File Name : f5_upload_cert_Create_client_ssl_v1.py
# -*- coding: utf-8 -*-
# f5 自動匯入憑證 並建立完成 Client-ssl
# 測試環境 Version : BIG-IP 15.1.0 Build 0.0.31 Final
# 匯入套件
#===============================================================================

def _upload(host, creds, fp):
    print(fp)
    #time.sleep(2)
    chunk_size = 512 * 1024
    headers = {
        'Content-Type': 'application/octet-stream'
    }
    fileobj = open(fp, 'rb')
    print(fileobj)
    time.sleep(2)
    filename = os.path.basename(fp)
    uri = 'https://%s/mgmt/shared/file-transfer/uploads/%s' % (host, filename)

    requests.packages.urllib3.disable_warnings()
    size = os.path.getsize(fp)

    start = 0

    while True:
        file_slice = fileobj.read(chunk_size)
        if not file_slice:
            break

        current_bytes = len(file_slice)
        if current_bytes < chunk_size:
            end = size
        else:
            end = start + current_bytes

        content_range = "%s-%s/%s" % (start, end - 1, size)
        headers['Content-Range'] = content_range
        requests.post(uri,
                      auth=creds,
                      data=file_slice,
                      headers=headers,
                      verify=False)

        start += current_bytes


def create_cert_obj(bigip, b_url, files):

    f1 = os.path.basename(files[0])
    f2 = os.path.basename(files[1])
    if f1.endswith('.crt'):
        certfilename = f1
        keyfilename = f2
    else:
        keyfilename = f1
        certfilename = f2

    certname = f1.split('.')[0]

    payload = {}
    payload['command'] = 'install'
    payload['name'] = timestr + '_Star_' + certname

    # Map Cert to File Object
    payload['from-local-file'] = '/var/config/rest/downloads/%s' % certfilename
    bigip.post('%s/sys/crypto/cert' % b_url, json.dumps(payload))

    # Map Key to File Object
    payload['from-local-file'] = '/var/config/rest/downloads/%s' % keyfilename
    bigip.post('%s/sys/crypto/key' % b_url, json.dumps(payload))

    # Map chain to File Object
    #payload['from-local-file'] = '/var/config/rest/downloads/%s' % keyfilename
    #bigip.post('%s/sys/crypto/chain' % b_url, json.dumps(payload))

    return certfilename, keyfilename


def create_ssl_profile(bigip, b_url, certname, keyname):
    print(" 2 show {} , {} ".format(certname, keyname))
    payload = {}
    #payload['name'] = certname.split('.')[0]
    payload['name'] = 'SNI_Star_' + timestr + '_' + certname.split('.')[0]
    payload['defaultsFrom'] = 'clientssl-secure'
    payload['ciphers'] = 'none'
    payload['cipherGroup'] = 'f5-secure'
    payload['cert'] = timestr + '_Star_' + certname.split('.')[0]
    payload['key'] = timestr + '_Star_' + keyname.split('.')[0]
    payload['chain'] = timestr + '_Star_' + certname.split('.')[0]
    bigip.post('%s/ltm/profile/client-ssl' % b_url, json.dumps(payload))

    #return certname, keyname


if __name__ == "__main__":
    import os, requests, json, argparse, getpass
    import time
    #timestr = time.strftime("%Y_%m%d_%H%M%S")
    timestr = time.strftime("%Y_%m%d_%H%M")
    parser = argparse.ArgumentParser(description='Upload Key/Cert to BIG-IP')

    parser.add_argument("host", help='BIG-IP IP or Hostname', )
    parser.add_argument("username", help='BIG-IP Username')
    parser.add_argument("filepath", nargs=2, help='Key/Cert file names (include the path.)')
    args = vars(parser.parse_args())

    hostname = args['host']
    username = args['username']
    filepath = args['filepath']

    print ("%s, enter your password: " % args['username']),
    password = getpass.getpass()
    print(filepath[0])
    print(filepath[1])
    print(os.path.dirname(os.path.abspath(__file__)))

    # Build the auth object for uploading the cert/key
    b_url_base = 'https://%s/mgmt/tm' % hostname
    b = requests.session()
    b.auth = (username, password)
    b.verify = False
    b.headers.update({'Content-Type':'application/json'})

    #upload the key/cert files to BIG-IP. Default location is /var/config/rest/downloads/
    #time.sleep(1)
    _upload(hostname, (username, password), filepath[0])
    _upload(hostname, (username, password), filepath[1])

    # Map the key/cert files to a BIG-IP cert file object for use in ssl profiles
    certname, keyname = create_cert_obj(b, b_url_base, filepath)
    print("show {} , {} ".format(certname, keyname))

    # Use the new cert file object to create an ssl profile
    create_ssl_profile(b, b_url_base, certname, keyname)
