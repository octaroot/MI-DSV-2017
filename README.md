# How to run #

```
git clone git@gitlab.fit.cvut.cz:cernama9/MI-DSV-termproject.git
cd MI-DSV-termproject
cd src
docker pull cernama9/php72-pthreads-readline
docker run --rm -it -v $(pwd):/app cernama9/php72-pthreads-readline ./start.php
```

You may also want to kill any leftover running docker containers:
```bash
for x in $(docker ps -q); do docker kill $x; done
```
