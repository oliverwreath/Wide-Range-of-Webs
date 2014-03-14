tmp = [85, 42, 21, 64, 68, 70, 84]

def print_grades(scores):
    for grade in scores:
        print grade

def grades_sum(scores):
    total = 0
    for grade in scores: 
        total += grade
    return total
    
def grades_average(scores):
    sum_of_grades = grades_sum(scores)
    average = sum_of_grades / float(len(scores))
    return average

def grades_variance(scores):
    average = grades_average(scores)
    variance = 0
    for i in scores:
        variance += (average - i) ** 2.0
    return variance / float(len(scores))
    
def grades_std_deviation(variance):
    return variance ** (1.0/2.0)
    
