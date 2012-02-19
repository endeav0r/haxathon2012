local PASSWORD = "thiswillgetyouin\n"

local socket = require("socket")
local lfs    = require("lfs")


function handle_client (client)
    client:send("Welcome to the Potent Pwnables message server\n " ..
                "Please enter the message server password\n" ..
                "Password: ")
    
    -- AUTH USER PASSWORD
    local password_index = 1
    while password_index <= #PASSWORD do
        local char = client:receive(1)
        if char == nil then
            client:close()
            return
        end

        if string.sub(PASSWORD, password_index, password_index) == char then
            password_index = password_index + 1
        else
            client:send("\nwrong password\n")
            client:close()
            return
        end
    end

    client:send("\nAccess Granted\n\n")

    client:send("\nOptions\n" ..
                " l <type>        : list messages\n" ..
                " r <type>/<id>   : read message <id>\n" ..
                "Message Types: admin, general\n")
    while true do
        client:send("?> ")
        local input_line  = client:receive("*l")
        client:send("\n")
        local first_space = string.find(input_line, " ")
        if first_space == nil or first_space < 2 then
            client:send("invalid command\n")
        else
            local command     = string.sub(input_line, 1, first_space - 1)
            local argument    = string.sub(input_line, first_space + 1)

            if command == "l" then
                for filename in lfs.dir(argument) do
                    client:send(filename .. "\n")
                end
            elseif command == "r" then
                local fh = io.open(argument, "r")
                client:send(fh:read("*a"))
                fh:close()
            else
                client:send("invalid command\n")
            end
        end
    end
end

local server = assert(socket.bind("*", 9001))

while true do
    local client = server:accept()
    local co = coroutine.create(handle_client)
    coroutine.resume(co, client)
end
