#include <stdio.h>
#include <string.h>

#include "rc4.h"

int main ()
{
    int i;
    unsigned char plaintext[] = "pedia";
    unsigned char ciphertext[32];

    rc4_crypt(plaintext, ciphertext, 5);

    printf("10 21...\n");
    for (i = 0; i < 5; i++)
        printf("%02x ", ciphertext[i]);

    printf("\n");

    return 0;
}
