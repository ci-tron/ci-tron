How ci-tron establish communication with runners
================================================

Ci-tron app contains a WebSocket server that allow runners to connect and wait for instructions.

All the connections are using WebSocket protocol.

Schema of ci-tron infrastructure
--------------------------------

```
   +-----------------------------+
   | ci-tron application (front) |-------
   +-----------------------------+      | The front application can retrieve the state of the build.
                                        |
                                        v
                    +--------------------------+
          --------> | ci-tron WebSocket Server |<------------
          |         +--------------------------+            |
          |              ^                                  | The front can't ask directly the launch of a build,
          |              |  They connect and wait           | the action is done only by the backend.
          |              |  for instruction.                |
          |              |                                  |
   +-----------+   +-----------+                            |
   | runner A  |   |  runner B |                            |
   +-----------+   +-----------+                            |
                                                            |
                                          +-------------------------------+
                                          | ci-tron application (backend) |
                                          +-------------------------------+
```

Connect as runner
-----------------

### 1. Runner handshake

The runner starts by saying what is his type:

```
RUNNER:init:{"type": "simple"}
```

### 2. Running a build

The runner receive from the server:

```
run:{"repo":"url of the repository",'script': ["lines", "of", "instructions"]}
```

The runner sends the state of the build in real time:

```
RUNNER:process:{"finished": false, "log": "Output"}
```

When the build is finished, the runner sends a process message with finish true:

```
RUNNER:process:{"finished": true, "success": true}
```

Connect as front app
--------------------

TODO

Connect as ci-tron
------------------

TODO
