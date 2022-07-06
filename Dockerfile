FROM maven:3.8.6-openjdk-18 AS build

COPY src /usr/src/app/src
COPY pom.xml /usr/src/app

RUN mvn -f /usr/src/app/pom.xml package

FROM openjdk:18.0.1

COPY --from=build /usr/src/app/target/*.jar /usr/app/effective-succotash.jar
EXPOSE 8080

ENTRYPOINT ["java", "-jar", "/usr/app/effective-succotash.jar"]