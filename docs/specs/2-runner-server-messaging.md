How ci-tron establish communication with runners
================================================

Ci-tron app contains a WebSocket server that allow runners to connect and wait for instructions.

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

TODO

Connect as front app
--------------------

TODO

Connect as ci-tron
------------------

TODO
