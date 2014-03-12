n = [[1, 2, 3], [4, 5, 6, 7, 8, 9]]
# Add your function here

def flatten(lstOflst):
    result = []
    for lst in lstOflst:
        result += lst
    return result
    


print flatten(n)
