#include <stdio.h>
#include <string.h>

char commands [3][8] = {
    "\xF1\xF2\xA1\xAC\xC0", // ps -A
    "\xE5\xE7\xA1\xAC\xE9", // df -h
    "\xE7\xF3\xE4\xE4"      // free
};


void deobfuscate (char * string)
{
    int i, len;

    len = strlen(string);
    for (i = 0; i < len; i++)
        string[i] ^= 0x81;
}

int main (int argc, char * argv[])
{
    int i;
    int command;

    if (argc != 2)
        command = -1;
    else
        command = atoi(argv[1]);
    
    if (    (command != 1)
         && (command != 2)
         && (command != 3)) {
        printf("Potent Pwnables Remote Monitoring\n");
        printf("usage: %s <option>\n", argv[0]);
        printf("options:\n");
        printf(" 1  list processes\n");
        printf(" 2  show free disk space\n");
        printf(" 3  show memory usage\n");
        return 0;
    }

    for (i = 0; i <3; i++)
        deobfuscate(commands[i]);

    switch (command) {
    case 1 : do_command(commands[0]); break;
    case 2 : do_command(commands[1]); break;
    case 3 : do_command(commands[2]); break;
    }

    return 0;
}
