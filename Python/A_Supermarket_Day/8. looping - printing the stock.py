stock = {"banana": 6,
"apple": 0,
"orange": 32,
"pear": 15}


price = {"banana": 4,
"apple": 2,
"orange": 1.5,
"pear": 3}

for item in stock:
    print item
    print "price: " + str(price[item])
    print "stock: " + str(stock[item])
