grades = [100, 100, 90, 40, 80, 100, 85, 70, 90, 65, 90, 85, 50.5]

def grades_sum(lst):
    res = 0
    for i in lst:
        res += i
    return res

print grades_sum(grades)
