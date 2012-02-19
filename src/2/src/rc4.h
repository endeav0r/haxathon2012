#ifndef rc4_HEADER
#define rc4_HEADER

void rc4_ksa   (unsigned char * block, unsigned char * key, int key_size);
int  rc4_crypt (unsigned char * input,
                unsigned char * output,
                int data_size);

#endif
