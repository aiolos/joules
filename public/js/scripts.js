/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
    return str;
}

function timeNotation(string) {
    string = pad(string, 4);
    return string.substr(0,2) + ':' + string.substr(2,2)
}

