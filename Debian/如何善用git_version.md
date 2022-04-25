# 善用 git version



```
echo "web v1.0 project" > index.html
git add .
git commit -m "v1.0"
git tag -a "v1.0" -m "v1.0"
git push origin v1.0

echo "web v1.1 project" > index.html
git add .
git commit -m "v1.1"
git tag -a "v1.1" -m "v1.1"
git push origin v1.1

echo "web v1.2 project" > index.html
git add .
git commit -m "v1.2"
git tag -a "v1.2" -m "v1.2"
git push origin v1.2

echo "web v2.0 project" > index.html
git add .
git commit -m "v2.0"
git tag -a "v2.0" -m "v2.0"
git push origin v2.0




echo "web v2.0 project" > index.html
git add .
git commit -m "NS20220101a001-v2.0"
git tag -a "NS20220101a001-v2.0" -m "NS20220101a001-v2.0"
git push origin NS20220101a001-v2.0
```
