def censor(text, target):
    lst = text.split()
    for idx, w in enumerate(lst):
        if w == target:
            lst[idx] = "*" * len(target)
    return " ".join(lst)
