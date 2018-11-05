1. LOGIN API:
URL- http://localhost/test/v1/api/login
method- post
params- username, password


2. GYM LIST API:
URL- http://localhost/test/v1/api/getGymsList
method- post
params- owner_id


3. Add GYM API:
URL- http://localhost/test/v1/api/addNewGym
method- post
params- owner_id, gym_name, gym_address, gym_number, morning_from, morning_to, evening_from, evening_to


4. EDIT GYM API:
URL- http://localhost/test/v1/api/editGym
method- post
params- owner_id, gym_id, gym_name, gym_address, gym_number, morning_from, morning_to, evening_from, evening_to


4. CHANGE GYM STATUS API:
URL- http://localhost/test/v1/api/changeGymStatus
method- post
params- owner_id, gym_id, status(0,1)