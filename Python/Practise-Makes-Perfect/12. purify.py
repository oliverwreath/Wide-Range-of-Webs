def purify(lst):
    res = []
    for idx, i in enumerate(lst):
        if i % 2 == 0:
            res.append(i)
    return res
