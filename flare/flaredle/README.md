After downloading the suorce code we will start analyzing it. Looking at the declaration at the top of this, we can see that this script.js file is pulling data from the words.js file and it seems that one of them is the correct line.
in the declaration reveals that CORRECT_GUESS is 57 .

![alt text](https://github.com/TaiPhung217/CTF_writeup/blob/main/flare/image/Screenshot%202022-10-12%20090207.png)

We add a piece of code:
console.log(rightGuessString);
to print out the correct line.

![alt text](https://github.com/TaiPhung217/CTF_writeup/blob/main/flare/image/Screenshot%202022-10-12%20090241.png)

and enter the keyboard. so it is possible to get flag.

flag: flareonisallaboutcats@flare-on.com
