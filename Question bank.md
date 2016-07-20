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
Write an interface **IVehicle** that exposes a method called **Drive** that acceps an integer parameter named **miles**.

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
