# How to run #

```
git clone
cd
docker pull cernama9/php72-pthreads-readline
docker run --rm -it -v $(pwd):/app cernama9/php72-pthreads-readline ./start.php
```

You also may want to kill any leftover docker containers:
```bash
for x in $(docker ps -q); do docker kill $x; done
```