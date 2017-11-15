from os import system
import curses
import hashlib

# GLOBAL VARIABLES

#print(hashlib.sha512("cleartext".encode("utf-8")).hexdigest())

un = "" # Cleartext login username, or empty string to require inputting username every time the script runs
pw = "" # SHA512 hash of your: username+md5(password), or empty string to require password every time the script runs

# DEFINED FUNCTIONS

def get_param(prompt_string):
	screen = curses.initscr()
	
	screen.clear()
	screen.border(0)
	screen.addstr(2, 2, prompt_string)
	screen.refresh()
	input = screen.getstr(10, 10, 60)
	
	curses.endwin()
	
	return input

def execute_cmd(cmd_string):
	system("clear")
	a = system(cmd_string)
	print("")
	if a == 0:
		print("Command executed correctly")
	else:
		print("Command terminated with error")
	raw_input("Press enter")
	print("")

def login(un, pw):
	if un == "":
		un = get_param("Enter the username").decode("UTF-8")
		pw = hashlib.sha512(un.encode("UTF-8") + hashlib.md5(get_param("Enter the password")).hexdigest().encode("UTF-8")).hexdigest()
	elif pw == "":
		pw = hashlib.sha512(un.encode("UTF-8") + hashlib.md5(get_param("Enter the password")).hexdigest().encode("UTF-8")).hexdigest()
	quit

# PROGRAM STARTS

screen = curses.initscr()

login(un, pw)

# x = 0
# while x != ord("4"):
# 	screen.clear()
# 	screen.border(0)
# 	screen.addstr(2, 2, "Please enter a number...")
# 	screen.addstr(4, 4, "1 - Add a user")
# 	screen.addstr(5, 4, "2 - Restart Apache")
# 	screen.addstr(6, 4, "3 - Show disk space")
# 	screen.addstr(7, 4, "4 - Exit")
# 	screen.refresh()
# 	
# 	x = screen.getch()
# 	
# 	if x == ord("1"):
# 		username = get_param("Enter the username")
# 		homedir = get_param("Enter the home directory, eg /home/nate")
# 		groups = get_param("Enter comma-separated groups, eg adm,dialout,cdrom")
# 		shell = get_param("Enter the shell, eg /bin/bash:")
# 		curses.endwin()
# 		execute_cmd("useradd -d " + homedir + " -g 1000 -G " + groups + " -m -s " + shell + " " + username)
# 	if x == ord("2"):
# 		curses.endwin()
# 		execute_cmd("apachectl restart")
# 	if x == ord("3"):
# 		curses.endwin()
# 		execute_cmd("df -h")

curses.endwin()
