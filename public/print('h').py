#1 2 3 4 -9
#10
l=list(map(int,input().split()))
c=0
s=0
for i in l:
    if i>0:
        c=c+i
    if c>s:
        c=s
print(c)