from bigrest.bigip import BIGIP
from time import sleep
import argparse
import getpass
import sys


def build_parser():
    parser = argparse.ArgumentParser()
    parser.add_argument("host", help="BIG-IP IP/FQDN")
    parser.add_argument("user", help="BIG-IP Username")

    return parser.parse_args()


def instantiate_bigip(host, user):
    pw = getpass.getpass(prompt=f"\n\tWell hello {user}, please enter your password: ")
    try:
        obj = BIGIP(host, user, pw, session_verify=False)
    except Exception as e:
        print(f"Failed to connect to {args.host} due to {type(e).__name__}:\n")
        print(f"{e}")
        sys.exit()
    return obj


def deploy_script():
    # slurp the file
    with open('poolstats.tcl') as f:
        tmsh_script = f.read()
    try:
        cli_script = {'name': 'poolstats.tcl', 'apiAnonymous': tmsh_script}
        b.create('/mgmt/tm/cli/script', cli_script)
    except Exception as e:
        print(f'{e}')
        sys.exit()


def run_poolstats(b):
    try:
        data = {'command': 'run', 'name': '/Common/poolstats.tcl', 'utilCmdArgs': ''}
        b.command('/mgmt/tm/cli/script', data)
    except Exception as e:
        print(f'{e}')


def download_poolstats_data(b):
    try:
        b.download('/mgmt/cm/autodeploy/software-image-downloads', 'poolstats.csv')
    except Exception as e:
        print(f'{e}')


if __name__ == "__main__":
    args = build_parser()
    b = instantiate_bigip(args.host, args.user)

    if not b.exist('/mgmt/tm/cli/script/poolstats.tcl'):
        deploy_script()

    run_poolstats(b)

    # might need to add a delay here if the file write takes a long time with big configs
    # sleep(30)
    download_poolstats_data(b)
