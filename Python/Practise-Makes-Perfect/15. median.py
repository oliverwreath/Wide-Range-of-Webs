def median(lst):
    i = len(lst)
    if i < 1:
        return -1
    lst.sort()
    if i % 2 == 1:
        return lst[i / 2]
    else:
        return (lst[i / 2] + lst[i / 2 -1]) /2.0
