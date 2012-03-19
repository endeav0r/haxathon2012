import socket
import os
import thread

PASSWORD = 'thiswillgetyouin\n'

def handle_client (sock) :
    sock.send('Welcome to the Potent Pwnables message server\n' + \
              'Please enter the message server password\n' + \
              'Password: ')

    password_index = 0
    while password_index < len(PASSWORD) :
        char = sock.recv(1)
        if not char :
            return
        if char == PASSWORD[password_index] :
            password_index += 1
        else :
            sock.send('\nwrong password\n')
            sock.close()
            return

    sock.send('\nAccess Granter\n\n')

    sock.send('Options\n' + \
              ' l <type>       : list message\n' + \
              ' r <type>/<id>  : read message <id>\n' + \
              'Message Types: admin, general\n')
    while True :
        sock.send('?> ')
        command = sock.recv(1024)
        if not command :
            return
        command = command.split('\n')[0].strip()
        sock.send('\n')
        pieces = command.split(' ', 1)
        if pieces[0] == 'l' :
            sock.send('\n'.join(os.listdir(pieces[1])) + '\n')
        elif pieces[0] == 'r' :
            fh = open(pieces[1], 'r')
            sock.sendall(fh.read() + '\n')
            fh.close()
        else :
            sock.send('invalid command\n')
    sock.close()

sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
sock.bind(('', 9001))
sock.listen(5)

while True :
    client, addr = sock.accept()
    print 'connection from: ' + str(addr)
    thread.start_new_thread(handle_client, (client,))
