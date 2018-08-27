declare @t time;
declare @dt datetime;
set @t='5:17 Am';print @t;
set @t='17:30'print @t;
set @t='07:30 PM';print @t;

set @dt='2017/05/10 05:00 pm';print @dt;

select CONVERT(varchar(10),@dt,103)

--select convert(varchar,@t,0);
--select  STUFF(RIGHT(' ' + CONVERT(VarChar(7),@t, 0), 7), 6, 0, ' ')
--select @dt;
select  CONVERT(varchar(10),@dt,103)+STUFF(RIGHT(' ' + CONVERT(VarChar(7),cast(@dt as time), 0), 7), 6, 0, ' ')
