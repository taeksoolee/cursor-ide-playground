# PHP Todo App

간단한 PHP Todo 애플리케이션입니다. Docker를 사용하여 PHP와 MySQL 환경을 구성했습니다.
이 앱은 커서를 활용하여 제작했습니다.

## 시작하기

1. Docker와 Docker Compose가 설치되어 있어야 합니다.

2. 프로젝트를 클론하고 디렉토리로 이동합니다:

```bash
git clone <repository-url>
cd <project-directory>
```

3. Docker 컨테이너를 빌드하고 실행합니다:

```bash
docker-compose up --build
```

4. 웹 브라우저에서 다음 주소로 접속합니다:

```
http://localhost:8080
```

## db 수정후 초기화

```
docker-compose down && docker volume rm php-testing_mysql_data
```

## 기능

- Todo 항목 추가
- Todo 항목 완료/미완료 토글
- Todo 항목 삭제
- 설명 추가 (선택사항)

## 기술 스택

- PHP 8.2
- MySQL 8.0
- Apache
- Bootstrap 5
