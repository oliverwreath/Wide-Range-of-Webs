def is_prime(x):
    if x < 2:
        return False
    else: 
        for i in range(2, x/2+1):
            if x % i == 0:
                return False
    return True
