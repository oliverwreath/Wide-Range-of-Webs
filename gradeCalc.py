lloyd = {
    "name": "Lloyd",
    "homework": [90.0, 97.0, 75.0, 92.0],
    "quizzes": [88.0, 40.0, 94.0],
    "tests": [75.0, 90.0]
}
alice = {
    "name": "Alice",
    "homework": [100.0, 92.0, 98.0, 100.0],
    "quizzes": [82.0, 83.0, 91.0],
    "tests": [89.0, 97.0]
}
tyler = {
    "name": "Tyler",
    "homework": [0.0, 87.0, 75.0, 22.0],
    "quizzes": [0.0, 75.0, 78.0],
    "tests": [100.0, 100.0]
}

# Add your function below!
def average(lst):
    sum = 0
    for item in lst:
        sum = sum + item
    return sum / len(lst)
   
def get_average(student):
    sum = 0
    sum = sum + average(student["homework"]) * 0.1
    sum = sum + average(student["quizzes"]) * 0.3 
    sum = sum + average(student["tests"]) * 0.6
    return sum
   
def get_letter_grade(score):
    if score >= 90:
        return "A"
    elif score >= 80:
        return "B"
    elif score >= 70:
        return "C"
    elif score >= 60:
        return "D"
    else :
        return "F"
       
print get_letter_grade(get_average(lloyd))

def get_class_average(lst):
    sum = 0
    for student in lst:
        sum = sum + get_average(student)
    return sum / len(lst)
   
students = [lloyd, alice, tyler]

avg = get_class_average(students)
print avg
print get_letter_grade(avg)
