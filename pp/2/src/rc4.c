#include "rc4.h"

#include <stdio.h>
#include <string.h>

unsigned char KEY[] = {
    0xd5, 0x0c, 0xb5, 0x2b,
    0xca, 0x1d, 0x2b, 0x45
};
#define KEY_SIZE 8

void rc4_ksa (unsigned char * S,
              unsigned char * key,
              int key_size)
{
    int i;
    unsigned char j;
    unsigned char tmp;

    for (i = 0; i < 256; i++) {
        S[i] = i;
    }

    j = 0;
    for (i = 0; i < 256; i++) {
        j += S[i] + key[i % key_size];
        tmp = S[i];
        S[i] = S[j];
        S[j] = tmp;
    }
}

int rc4_crypt (unsigned char * input,
               unsigned char * output,
               int data_size) {
    int i, j, k, tmp;
    unsigned char S [256];

    memset(S, 0, 256);

    rc4_ksa(S, KEY, KEY_SIZE);

    j = 0;
    i = 0;
    for (k = 0; k < data_size; k++) {
        i = (i + 1) % 256;
        j = (j + S[i]) % 256;
        tmp = S[i];
        S[i] = S[j];
        S[j] = tmp;
        output[k] = input[k] ^ S[(S[i] + S[j]) % 256];
    }

    return 0;
}
