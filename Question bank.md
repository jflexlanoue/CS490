# Question Bank

## Primary Question

Write a function called compute which takes in 3 arguments, The first 2 arguments are integers and the last is a char containing one of these four special characters + , - , *  or  /. The function should apply the symbol as an operation on the two integers and return the result as an integer. If the symbol is not one of these 4, the function should return -1.

Solution:
```java
  public int compute(int a, int b, char op)
  {
    if(op == '+'){
      return a+b;
    } else if(op == '-'){
      return a-b;
    }else if(op == '*'){
      return a-b;
    }else if(op == '/'){
      return a/b;
    } else{
      return -1;
    }
}
```

Test Cases:

"5,10,'+'"
"20,10,'-'"
"50,420,'*'"
"60,3,'/'"
"60,3,'.'"



## 1
Write a function called **Car** that accepts an integer argument. The function should print the line "Vroom Vroom" in the console and not return anything.

```

Solution:
```java
  public void Car(int miles)
  {
    System.out.println("Vroom Vroom");
  }
```

## 2
Write a function which takes an integer parameter in miles and returns the value converted to kilometers as a double. 


Solution:
```java
  public double Drive(int miles){
    return miles * 1.6d;
  }
```

Test Cases:
"10"
"20"
"50"

# 3
Write a function named **doNothing** that takes no parameters and returns no data.

Solution:
```java
public void doNothing()
{

}
```
Test Cases:

""
" "


# 4
Write a function named **ImplementationDetails** that takes no parameters and returns an int. The function should return the value 52;

Solution:
```java
public int ImplementationDetails()
{
  return 52;
}
```
Test Cases:

""
" "



# 5
Write the full function call to get a random number between 0 and 1

Solution:
```java
Math.random();
```

# 6 
Write a line of code which creates a Student object **stu** with the name **"John Smith"** and the grade *'B'*. 

Solution:
```java
Student stu = new Student("John Smith", 'B');
```
# 7
Write a function called MyLoop which contains a for-loop that prints a list of the countdown from thirty to zero by threes.

```java

public void MyLoop(){
for (i = 30; i >=0; i-= 3){
  System.out.println(i);
}
}

Test Cases:

""
" "


```
# 8 
Write a function that returns the square root of 121. 

Solution:
```java
public double Square(){
  return Math.sqrt(121);
}

Test Cases:

""
" "

```
# 9 
Write a function which takes in an integer and print "divisible" if it is "**divisble**" by 7 otherwhise it should print "**not divisible**"


Solution:
```java

public void Div(int i){
if (i % 7 == 0)
  System.out.println("divisible");
else
  System.out.println("not divisible");
}
```
Test Cases:
"7"
"21"
"23"
"151"


# 10 
Write a method named **skipElement** that accepts an array **arr** and prints, in a single line, every other element, starting with the first element.
```java
public static void skipElement (int[] arr){
  for (int i=0; i<arr.length; i+=2)
    System.out.print (arr[i] + " ");
}
```

Test Cases:
"new int[] { 5, 3, 2, 1, 14, 122, 1, 77}"
"new int[] { 1, 5, 5, 6, 7, 12, 124, 555, 66, 20, 30, 55}"


