## Laravel API CRUD User with JWT

### Instruksi

1. Clone atau Download ke lokal
2. Masuk ke direktori yang sudah di Clone/Download `cd your-name-directory`
3. Jalankan semua perintah untuk instalasi laravel seperti `composer install` `npm install`
4. Jalankan perintah `php artisan jwt:secret` untuk membuat secret jwt di file `.env`
5. Jalankan perintah `php artisan migrate` untuk membuat tabel melalui migration
6. Jalankan perintah `php artisan db:seed` untuk mengisi data di tabel dengan data seeder
7. Jalankan perintah `composer run dev` untuk menjalankan program dalam development

### List Endpoints

| API Endpoint    | Method | Deksripsi                        |
| --------------- | ------ | -------------------------------- |
| `/api/register`| POST | Register User Baru |
| `/api/login`| POST | Login User |
| `/api/users`| GET | Menampilkan semua data user |
| `/api/users/:id`| GET | Menampilkan data user sesuai params ID |
| `/api/users` | POST | Menambahkan user baru |
| `/api/users/:id` | PUT | Mengubah data user sesuai params ID |
| `/api/users/:id` | DELETE | Menghapus data user sesuai params ID |
| `/api/users/:id/hobby` | POST | Menambahkan hobi baru sesuai dengan params ID user |
| `/api/hobby/:id` | DELETE | Menghapus data hobi sesuai params ID |

### Contoh Data

- **Register User Baru**

  - **Request**

    - Endpoint : `/api/register`
    - Method : `POST`
    - Body : `RAW JSON`
    - ```json
      {
          "name": "Zone",
          "email": "zone@mail.com",
          "password": "zone93",
          "password_confirmation": "zone93"
      }
      ```
  - **Response**

  ```json
  {
      "message": "User succesfully registered",
      "user": {
          "name": "Zone",
          "email": "zone@mail.com",
          "role": "user",
          "updated_at": "2025-06-24T13:20:06.000000Z",
          "created_at": "2025-06-24T13:20:06.000000Z",
          "id": 3
        }
  }
  ```

- **Login User**

  - **Request**

    - Endpoint : `/api/login`
    - Method : `POST`
    - Body : `RAW JSON`
    - ```json
      {
          "email": "superadmin@mail.com",
          "password": "superadmin",
      }
      ```
  - **Response**

  ```json
  {
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzUwNzcxMzc0LCJleHAiOjE3NTA3NzQ5NzQsIm5iZiI6MTc1MDc3MTM3NCwianRpIjoidHdqd01sU0RZc3lxbVFtVyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3Iiwicm9sZSI6InN1cGVyYWRtaW4iLCJlbWFpbCI6InN1cGVyYWRtaW5AbWFpbC5jb20ifQ.unA1x46HyhOHUwTTJm633snR_7htMdvwTM9377kcY8o",
      "token_type": "bearer",
      "expires_in": 3600,
      "user": {
          "id": 1,
          "name": "Super Admin",
          "email": "superadmin@mail.com",
          "email_verified_at": "2025-06-24T13:14:19.000000Z",
          "role": "superadmin",
          "created_at": "2025-06-24T13:14:19.000000Z",
          "updated_at": "2025-06-24T13:14:19.000000Z"
      }
  }
  ```

- **Menampilkan semua data user**

  - **Request**

    - Endpoint : `/api/users`
    - Method : `GET`
    - Authorization : `Bearer Token`

  - **Response**

  ```json
  {
      {
          "id": 1,
          "name": "Super Admin",
          "email": "superadmin@mail.com",
          "email_verified_at": "2025-06-24T13:14:19.000000Z",
          "role": "superadmin",
          "created_at": "2025-06-24T13:14:19.000000Z",
          "updated_at": "2025-06-24T13:14:19.000000Z",
          "hobis": []
      },
      {
          "id": 2,
          "name": "Zone",
          "email": "zone@mail.com",
          "email_verified_at": null,
          "role": "user",
          "created_at": "2025-06-24T13:20:06.000000Z",
          "updated_at": "2025-06-24T13:20:06.000000Z",
          "hobis": []
      },
  }
  ```

- **Menampilkan data user sesuai params ID**

  - **Request**

    - Endpoint : `/api/users/:id`
    - Params : `id: 2`
    - Method : `GET`
    - Authorization : `Bearer Token`

  - **Response**

  ```json
  {
      {
          "id": 2,
          "name": "Zone",
          "email": "zone@mail.com",
          "email_verified_at": null,
          "role": "user",
          "created_at": "2025-06-24T13:20:06.000000Z",
          "updated_at": "2025-06-24T13:20:06.000000Z",
          "hobis": []
      }
  }
  ```

- **Menambahkan user baru**

  - **Request**

    - Endpoint : `/api/users`
    - Method : `POST`
    - Authorization : `Bearer Token`
    - Body : `RAW JSON`
    - ```json
      {
          "name": "New User1",
          "email": "newuser1@example.com",
          "password": "user1",
          "role": "user",
          "hobis": [
              {"nama_hobi": "Gaming"},
              {"nama_hobi": "Coding"}
          ]
      }
      ```
  - **Response**

  ```json
  {
      "message": "User created successfully",
      "user": {
          "name": "New User1",
          "email": "newuser1@example.com",
          "role": "user",
          "updated_at": "2025-06-24T13:23:39.000000Z",
          "created_at": "2025-06-24T13:23:39.000000Z",
          "id": 3,
          "hobis": [
              {
                  "id": 1,
                  "user_id": 4,
                  "nama_hobi": "Gaming",
                  "created_at": "2025-06-24T13:23:39.000000Z",
                  "updated_at": "2025-06-24T13:23:39.000000Z"
              },
              {
                  "id": 2,
                  "user_id": 4,
                  "nama_hobi": "Coding",
                  "created_at": "2025-06-24T13:23:39.000000Z",
                  "updated_at": "2025-06-24T13:23:39.000000Z"
              }
          ]
      }
  }
  ```

  - **Mengubah data user sesuai params ID**

  - **Request**

    - Endpoint : `/api/users/:id`
    - Params : `id: 3`
    - Method : `PUT`
    - Authorization : `Bearer Token`
    - Body : `RAW JSON`
    - ```json
      {
          "name": "New User1 Updated",
          "email": "newuser1up@example.com",
          "password": "user1up",
          "role": "user",
          "hobis": [
              {"nama_hobi": "Gaming Valorant"},
              {"nama_hobi": "Coding PHP"}
          ]
      }
      ```
  - **Response**

  ```json
  {
      "message": "User updated successfully",
      "user": {
          "id": 2,
          "name": "New User1 Updated",
          "email": "newuser1up@example.com",
          "email_verified_at": null,
          "role": "user",
          "created_at": "2025-06-24T13:23:39.000000Z",
          "updated_at": "2025-06-24T13:46:53.000000Z",
          "hobis": [
              {
                  "id": 5,
                  "user_id": 4,
                  "nama_hobi": "Gaming Valorant",
                  "created_at": "2025-06-24T13:46:53.000000Z",
                  "updated_at": "2025-06-24T13:46:53.000000Z"
              },
              {
                  "id": 6,
                  "user_id": 4,
                  "nama_hobi": "Coding PHP",
                  "created_at": "2025-06-24T13:46:53.000000Z",
                  "updated_at": "2025-06-24T13:46:53.000000Z"
              }
          ]
      }
  }
  ```

  - **Menghapus data user sesuai params ID**

  - **Request**

    - Endpoint : `/api/users/:id`
    - Params : `id: 3`
    - Method : `DELETE`
    - Authorization : `Bearer Token`
  - **Response**

  ```json
  {
      "message": "User deleted successfully"
  }
  ```

  - **Menambahkan hobi baru sesuai dengan params ID user**

  - **Request**

    - Endpoint : `/api/users/:id/hobby`
    - Params : `id: 1`
    - Method : `PUT`
    - Authorization : `Bearer Token`
    - Body : `RAW JSON`
    - ```json
      {
          "nama_hobi": "Olahraga",
      }
      ```
  - **Response**

  ```json
  {
      "message": "Hobi added successfully",
      "hobi": {
          "nama_hobi": "Olahraga",
          "user_id": 1,
          "updated_at": "2025-06-24T13:38:47.000000Z",
          "created_at": "2025-06-24T13:38:47.000000Z",
          "id": 4
    }
  }
  ```

  - **Menghapus data hobi sesuai params ID**

  - **Request**

    - Endpoint : `/api/hobby/:id`
    - Params : `id: 4`
    - Method : `DELETE`
    - Authorization : `Bearer Token`
  - **Response**

  ```json
  {
      "message": "Hobi deleted successfully"
  }
  ```
