PROGRAM Zad1;
var p, zod, zdo, ile: integer;
arr: array of integer;

PROCEDURE load_values();
var a: integer;
    b: integer;
    c: integer;
BEGIN
writeln('podaj zakres od, do oraz ile: ');
readln(a, b, c);
 zod := a;
 zdo := b;
 ile := c;
END;

PROCEDURE randomize(zod,zdo,ile: integer);
var i: integer;
BEGIN
	SetLength(arr,ile);
	for i := 0 to ile do
	BEGIN    
	     arr[i] := random(zdo - zod + 1) + zod;
	END;
END;

PROCEDURE sorting();
var i: integer;
var j: integer;
var tmp: integer;
BEGIN
	for i := ile-1 downto 0 do
	BEGIN
            for j := 1 to i do
	    BEGIN 
	    	if (arr[j-1] > arr[j]) then
		BEGIN
		  tmp := arr[j-1];
	    	  arr[j-1] := arr[j];
	      	  arr[j] := tmp;
		END;
	    END;
	END;

END;

BEGIN
 load_values();
 randomize(zod,zdo,ile);
 writeln('przed posortowaniem:');
 for p := 0 to ile-1 do
 BEGIN
	writeln(p, ': ', arr[p]);
 END;
 	sorting();
 	writeln('posortowane:');
 	for p := 0 to ile-1 do
 BEGIN
	writeln(p, ': ', arr[p]);
 END;
 
END.

