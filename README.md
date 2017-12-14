# MI-DSV-2017
Distributed Systems and Computing course term project - Distributed chat application

## Assignment

### Implement a chat application

Program will allow users (nodes) to send messages to each other. All messages must have full ordering (for synchronization use leader or mutual exclusion). All nodes must have at least these functions: send message, login, logout, crash (exit without logout).


## Solution

This term project implements a system of nodes which form an unidirectional ring network. The nodes communicate with each other via network sockets. Any number of nodes can join or leave such network. There are protocols in place for handling an unexpected crash of a node (via heartbeat packets). Whenever the topology changes (due to nodes joining/leaving) a leader is elected. This is done via the _Chang-Roberts_ leader election algorithm.

### Implementation

This term project is implemented in PHP, which is not the best choice for problems such as this one. I picked PHP deliberately as a challenge. I had to use the development preview of PHP 7.2 which had community support of multithreading. Not all of the language features (mainly `resources`) are _thread-safe_, so I had to utilize `streams` instead of `resources`.

Nevertheless this repository contains a fully-functional, distributed, multi-threaded PHP _chat_ application.

In order to be able to simulate a large network of nodes, I picked Docker as a virtualization platform. This is due to it's low memory footprint, allowing for a large number of nodes running at once. It also has the benefit of nicely encapsulating the enviroment required for the application to work (PHP 7.2, compiled with the support for `readline` and `pthreads`).

### Starting a node

```bash
docker pull cernama9/php72-pthreads-readline
git clone git@github.com:octaroot/MI-DSV-2017.git
cd MI-DSV-2017/src

docker run --rm -it -v $(pwd):/app cernama9/php72-pthreads-readline ./start.php
```

Then follow the instruction (setting up interace and a port to listen on).


### How to get rid of any running Docker containers

```bash
for x in $(docker ps -q); do docker kill $x; done
```
