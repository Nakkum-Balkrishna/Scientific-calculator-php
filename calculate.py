import sys
from numpy import sin, cos, tan, cosh, tanh, sinh

def process_data(input_data):
    try:
        result = eval(input_data)
    except Exception as e:
        result = str(e)
    return result

if __name__ == "__main__":
    input_data = sys.argv[1]
    result = process_data(input_data)
    print(result)