# Функциональные тесты

## 1. signup

http://med.dev87.loc/api/signup

Input:
data={"username":"Vasya_Pupkin","password":"12345","name":"Василий","midname":"Иванович","surname":"Пупкин","birth":"25.11.1999","contacts":{"email":"vasya@pupkin.ru","phone":"89870123456","skype":"vasya_pupkin","fax":"cheza"},"kantara":"zos"}

Returns:
{
  "status":"OK"
}

## 2. login

http://med.dev87.loc/api/login

Input:
username=Vasya_Pupkin&password=12345

Returns:
{
  "userhash": "7b7f3c605f99f68af970da3122d32eea319c0470828a059b7e",
  "timelive": "2016-12-05T08:09:48+00:00",
  "status": "OK"
}

## 3. loginfo

http://med.dev87.loc/api/loginfo

Input:
userhash=7b7f3c605f99f68af970da3122d32eea319c0470828a059b7e

Returns:
{
  "timelive": "2016-12-05T08:09:48+00:00",
  "status": "OK"
}

## 4. logout

http://med.dev87.loc/api/logout

Input:
userhash=7b7f3c605f99f68af970da3122d32eea319c0470828a059b7e

Returns:
{
  "status": "OK"
}