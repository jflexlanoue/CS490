# Question Bank

## 1
Write a child class **Car** that inherits from **Vehicle** and overrides the **Drive** method. Inside the new function, print the distance traveled denoted in miles.

**Vechicle.java:**
```java
public class Vehicle
{
  public void Drive(int miles)
  {
    // throw not implemented exception
  }
}
```

Solution:
```java
public class Car extends Vehicle
{
  public void Drive(int miles)
  {
    System.out.println(miles + " miles");
  }
}
```

## 2
Write an interface **IVehicle** that exposes a method called **Drive** that accepts an integer parameter named **miles**.

Solution:
```java
public interface IVehicle
{
  public void Drive(int miles);
}
```

# 3
Write a function named **Drive** that takes no parameters and returns no data. It should create a new **Vehicle** instance and call the **Drive** method with a value of 20;

**Vechicle.java:**
```java
public class Vehicle
{
  public void Drive(int miles)
  {
    // Implementation details
  }
}
```

Solution:
```java
public void Drive()
{
  var vehicle = new Vehicle();
  vehicle.Drive(20);
}
```

# 4
Write a function named **ImplementationDetails** that takes no parameters, returns an int, and cannot be called by outside code. The function should return the value 52;

Solution:
```java
private int ImplementationDetails()
{
  return 52;
}
```

# 5
Write the full function call to get a random number between 0 and 1

Solution:
```java
Math.random();
```

# 6 Write a line of code which creates a Student object "stu" with the name "John Smith" and the grade "B". 

Solution:
```java
Student st = new Student("John Smith", 'C');
```
# 7 Write a for-loop that prints a list of the countdown from thirty to zero by threes. Use the variable **i**, and assume that **i** is already declared as an integer.
```java
for (i = 30; i >=0; i-= 3){
  System.out.println(i);
}
```
# 8 Write a line of code to return the square root of 121. 

Solution:
```java
Math.sqrt(121);
```
# 9 Write an if-else statement to determine whether or not an integer **i** is divisible by 7, printing either "divisble" or "not divisible".

Solution:
```java
if (i % 7 == 0)
  System.out.println("divisible");
else
  System.out.println("not divisible");
```

# 10 Write a method named **skipElement** that accepts an array **arr** and prints in a single line every other element, starting with the first element.
```java
public static void skipElement (int[] arr){
  for (int i=0; i<arr.length; i+=2)
    System.out.print (ar[i] + " ");
}
```
