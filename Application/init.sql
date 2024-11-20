USE MadTestLab;

CREATE TABLESPACE TDE
ADD DATAFILE 'TDE.ibd'
ENCRYPTION='Y';

ALTER INSTANCE ROTATE INNODB MASTER KEY;

SET block_encryption_mode = 'aes-256-cbc';

CREATE TABLE Accounts (
    AccountID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARBINARY(256) NOT NULL,
    Role VARCHAR(20) NOT NULL,
    AccountStatus VARCHAR(20) NOT NULL,
    Credentials VARCHAR(100) NOT NULL,
    IV VARBINARY(16) NOT NULL
) ENCRYPTION='Y';


CREATE TABLE Insurances (
    InsuranceID INT AUTO_INCREMENT PRIMARY KEY,
    InsuranceName VARCHAR(100) NOT NULL,
    InsuranceAmount DECIMAL(10, 2) NOT NULL,
    InsuranceDetails TEXT,
    InsuranceStatus VARCHAR(20) NOT NULL
) ENCRYPTION='Y';

CREATE TABLE Patients (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    InsuranceID INT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARBINARY(256) NOT NULL,
    Email VARBINARY(256) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID),
    FOREIGN KEY (InsuranceID) REFERENCES Insurances(InsuranceID)
) ENCRYPTION='Y';

CREATE TABLE Secretaries (
    SecretaryID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARBINARY(256) NOT NULL,
    Email VARBINARY(256) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
) ENCRYPTION='Y';

CREATE TABLE LabStaffs (
    LabStaffID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARBINARY(256) NOT NULL,
    Email VARBINARY(256) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
) ENCRYPTION='Y';

CREATE TABLE TestsCatalog (
    TestCode INT AUTO_INCREMENT PRIMARY KEY,
    TestName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    TestType VARCHAR(20) NOT NULL 
) ENCRYPTION='Y';

CREATE TABLE Appointments (
    AppointmentID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    SecretaryID INT NOT NULL,
    AppointmentDateTime DATETIME NOT NULL,
    AppointmentsStatus VARCHAR(20) NOT NULL, 
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (SecretaryID) REFERENCES Secretaries(SecretaryID)
) ENCRYPTION='Y';


CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    LabStaffID INT NOT NULL,
    SecretaryID INT NOT NULL,
    TestCode INT NOT NULL,
    OrderDateTime DATETIME NOT NULL,
    OrderStatus ENUM('Pending', 'Completed', 'Cancelled') NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (LabStaffID) REFERENCES LabStaffs(LabStaffID),
    FOREIGN KEY (SecretaryID) REFERENCES Secretaries(SecretaryID),
    FOREIGN KEY (TestCode) REFERENCES TestsCatalog(TestCode)
) ENCRYPTION='Y';


CREATE TABLE Bills (
    BillID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    InsuranceID INT NOT NULL,
    Amount DECIMAL(10, 2) NOT NULL,
    PaymentStatus ENUM('Paid', 'Unpaid', 'Pending') NOT NULL,
    BillDateTime DATETIME NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (InsuranceID) REFERENCES Insurances(InsuranceID)
) ENCRYPTION='Y';

CREATE TABLE Results (
    ResultID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    LabStaffID INT NOT NULL,
    ReportURL VARCHAR(255) NOT NULL,
    Interpretation TEXT NOT NULL,
    ResultDateTime DATETIME NOT NULL,
    ResultStatus VARCHAR(20) NOT NULL,  
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (LabStaffID) REFERENCES LabStaffs(LabStaffID)
) ENCRYPTION='Y';


INSERT INTO `Accounts` (`AccountID`, `Username`, `Password`, `Role`, `AccountStatus`, `Credentials`, `IV`) VALUES
(1, 'Tommer', 0x8d95509edacc3027a3b2e6841fd3bf7bcdc8f6b84dabe014e2a997272a5d0e3ad0f3323350b2cb661d1f0612798efe14030c5d24611c28ed0ae60b0c4d0113bf, 'Patient', 'active', 'default', 0xc3eecf84496bc4815a1685c83a737d7f),
(2, 'Secretary', 0xe3cc17ff9b63400ddd3fec32f01fcdeb6bef3edfaa6a6e1b0b0bd66cd3007998169bd3a1c0a143296863f465540b3f5bdf50c54ace5cbf85c06710dc2c60736e, 'Secretary', 'active', 'default', 0xe7fc846ad14a4695d7e83c65f2fde52e);


INSERT INTO `Patients` (`PatientID`, `AccountID`, `InsuranceID`, `FirstName`, `LastName`, `DateOfBirth`, `Gender`, `Phone`, `Email`) VALUES
(1, 1, NULL, 'Tommer', 'Ching', '2024-11-19', 'Male', 0x3267768ed6b3631c769d1f35af5fdfab, 0x4dbf5339f1e0c99cadda8736459b020a61da3f97819aae2aaf25079e83e114d5);

INSERT INTO `Secretaries` (`SecretaryID`, `AccountID`, `FirstName`, `LastName`, `DateOfBirth`, `Gender`, `Phone`, `Email`) VALUES
(1, 2, 'Chris', 'Wong', '2024-11-19', 'Male', 0xd7617b710ef4ef8c3a0134fada4ff8d4, 0x594856b2b2667f6d37f41cf54b72b05f);
