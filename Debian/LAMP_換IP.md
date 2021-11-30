# 換網卡和apache IP

```
sed -i 's/10.1.20/192.168.88/g' /etc/network/interfaces

換網路卡

sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/000-default


sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/default-ssl.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/dvwa.conf

sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/Server1.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/Server2.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/Server3.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/Server4.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-available/Server5.conf


sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-enabled/phishing.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-enabled/hackazon.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-enabled/csrf.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-enabled/demobank.conf
sed -i 's/10.1.20/192.168.88/g' /etc/apache2/sites-enabled/bank.conf
```
