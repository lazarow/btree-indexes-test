Atrybut 1 ma 50 różnych wartości, atrybut 2 ma ich 25, atrybut 3 75 oraz atrybut 4 ma ich 10;

Test dla 200000 rekordów:
```
Execution time of creating database structure = 1.7183887958527 sec.
Execution time of seeding random data = 3.243225812912 sec.
...attribute 1 has been cached
...attribute 2 has been cached
...attribute 3 has been cached
...attribute 4 has been cached
Execution time of creating cache = 27.332938909531 sec.
[====================================================================================================>] - 100% - 250/250
Average selection query time for raw data table = 0.059373275279999 sec.
[====================================================================================================>] - 100% - 250/250
Average selection query time for indexed caches = 0.0071746356487274 sec. // około 8x razy szybciej
```

Test dla 700000 rekordów:
```
Execution time of creating database structure = 1.694030046463 sec.
Execution time of seeding random data = 11.023061037064 sec.
...attribute 1 has been cached
...attribute 2 has been cached
...attribute 3 has been cached
...attribute 4 has been cached
Execution time of creating cache = 74.421314954758 sec.
[====================================================================================================>] - 100% - 250/250
Average selection query time for raw data table = 0.20541200637817 sec.
[====================================================================================================>] - 100% - 250/250
Average selection query time for indexed caches = 0.042865991830826 sec. // ok. 4.88x szybciej
```
