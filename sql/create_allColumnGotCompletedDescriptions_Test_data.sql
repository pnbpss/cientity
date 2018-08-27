use HDS;
SELECT 
'['''+replace(TABLE_NAME,'hds_','')+''', '''+COLUMN_NAME+'''],' provider1
,'['''+COLUMN_NAME+''',0],' provider2
,''''+replace(TABLE_NAME,'hds_','')+'''' provider31
,''''+COLUMN_NAME+'''' provider32
,'['''+replace(TABLE_NAME,'hds_','')+'''],' provider4
,'|' sp
,* 
FROM INFORMATION_SCHEMA.COLUMNS c
WHERE TABLE_NAME like 'hds_%' 
and TABLE_SCHEMA='dbo'
and TABLE_NAME not in ('hds_sysConfigs','hds_sysConfigTypes')
order by TABLE_NAME,c.ORDINAL_POSITION


use HDS;
SELECT 
--'['''+replace(TABLE_NAME,'hds_','')+''', '''+COLUMN_NAME+'''],' provider1
--,'['''+COLUMN_NAME+''',0],' provider2
''''+replace(TABLE_NAME,'hds_','')+'''' provider31
,''''+COLUMN_NAME+'''' provider32
--,'['''+replace(TABLE_NAME,'hds_','')+'''],' provider4
--,'|' sp
--,* 
FROM INFORMATION_SCHEMA.COLUMNS c
WHERE TABLE_NAME like 'hds_%' 
and TABLE_SCHEMA='dbo'
and TABLE_NAME not in ('hds_sysConfigs','hds_sysConfigTypes')
order by TABLE_NAME,c.ORDINAL_POSITION
