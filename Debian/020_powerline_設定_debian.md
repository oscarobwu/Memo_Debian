# Powerline for WSL Debian

### 在 shell 安裝

```

sudo apt update
sudo apt upgrade -y

apt install python-pip git -y

apt install powerline -y

apt install fonts-powerline -y

pip show powerline-status

pip install powerline-gitstatus

pip install psutil netifaces

pip install powerline-mem-segment

cd ~
git clone https://github.com/powerline/fonts.git
cd fonts
./install.sh
cd ..
rm -rf fonts

```
