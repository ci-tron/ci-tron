/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */


/**
 * This interface is not in use for now but will be useful because the back return this kind
 * of user as json.
 */
export interface User {
    username:string,
    slug:string,
    email?:string,
}
