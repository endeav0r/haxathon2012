#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <string.h>
#include <netdb.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <sys/socket.h>

#include <arpa/inet.h>

#define PORT "9997"
//#define HOST "localhost"
#define HOST "10.0.3.22"

#include "rc4.h"

int do_command (char * command)
{
    char tosend[8];
    char buf[1024];
    int data_size;

    struct addrinfo hints, * servinfo, *p;
    int rv, sockfd;

    data_size = strlen(command);
    rc4_crypt(command, tosend, data_size);

    memset(&hints, 0, sizeof(struct addrinfo));
    hints.ai_family = AF_UNSPEC;
    hints.ai_socktype = SOCK_STREAM;

    if ((rv = getaddrinfo(HOST, PORT, &hints, &servinfo)) != 0) {
        fprintf(stderr, "getaddrinfo: %s\n", gai_strerror(rv));
        return -1;
    }

    for (p = servinfo; p != NULL; p = p->ai_next) {
        if ((sockfd = socket(p->ai_family, p->ai_socktype, p->ai_protocol)) == -1)
            continue;
        if (connect(sockfd, p->ai_addr, p->ai_addrlen) == -1) {
            close(sockfd);
            continue;
        }
        break;
    }
    if (p == NULL) {
        fprintf(stderr, "client failed to connect\n");
        return -1;
    }

    send(sockfd, tosend, data_size, 0);
    
    memset(buf, 0, 1024);
    while ((data_size = recv(sockfd, buf, 1023, 0)) > 0) {
        buf[1023] = '\0';
        printf("%s\n", buf);
        memset(buf, 0, 1024);
    }

    close(sockfd);

    return 0;
}
