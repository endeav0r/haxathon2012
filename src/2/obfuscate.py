import base64

def obfuscate (string) :
    out = ''
    for s in string :
        out += chr(ord(s) ^ 0x81)
    return out

print base64.b16encode(obfuscate("ps -A"))
print base64.b16encode(obfuscate("df -h"))
print base64.b16encode(obfuscate("free"))

