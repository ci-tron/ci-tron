<?php
/**
 * This file is a part of a nekland library
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\WebSockets;


class ClientMock extends Client
{
    public function getRunnersAsJson()
    {
        return '
          [
            {
              "type": "simple",
              "state": "WAITING"
            }
          ]
        ';
    }
}
