<pre>
# upload one file
mc up file_C.txt

# upload two files
mc up file_C.txt level_1/file_C.txt

# upload a directory, and its subdirectories, recursively
mc up -r level_1

# upload everything in the current directory, and subdirectories, recursively
mc up -r .

# download one file
mc down file_B.txt

# download two files
mc down file_B.txt level_1/file_B.txt

# download a file (creating necessary directory level_1/level_2):
mc down level_1/level_2/file_A.txt

# download a directory and subdirectories, recursively:
mc down -r level_1

# download all files and subdirectories, recursively:
mc down -r .
</pre>