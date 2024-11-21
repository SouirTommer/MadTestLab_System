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
    IV VARBINARY(16) NOT NULL
) ENCRYPTION='Y';

CREATE TABLE Patients (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    AccountID INT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Phone VARBINARY(256) NOT NULL,
    Email VARBINARY(256) NOT NULL,
    FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID)
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
    LabStaffType VARCHAR(20) NOT NULL,
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
    TestType VARCHAR(30) NOT NULL 
) ENCRYPTION='Y';

CREATE TABLE Insurances (
    InsuranceID INT AUTO_INCREMENT PRIMARY KEY,
    InsuranceName VARCHAR(100) NOT NULL,
    InsuranceAmount DECIMAL(10, 2) NOT NULL,
    InsuranceDetails TEXT,
    InsuranceStatus VARCHAR(20) NOT NULL
) ENCRYPTION='Y';

CREATE TABLE Appointments (
    AppointmentID INT AUTO_INCREMENT PRIMARY KEY,
    LabStaffID INT NOT NULL,
    PatientID INT NOT NULL,
    SecretaryID INT NOT NULL,
    AppointmentDateTime DATETIME NOT NULL,
    AppointmentsStatus VARCHAR(20) NOT NULL, 
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    FOREIGN KEY (SecretaryID) REFERENCES Secretaries(SecretaryID),
    FOREIGN KEY (LabStaffID) REFERENCES LabStaffs(LabStaffID)
) ENCRYPTION='Y';


CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    LabStaffID INT NOT NULL,
    SecretaryID INT NOT NULL,
    TestCode INT NOT NULL,
    OrderDateTime DATETIME NOT NULL,
    OrderStatus VARCHAR(20) NOT NULL,
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
    PaymentStatus VARCHAR(20) NOT NULL,
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

INSERT INTO TestsCatalog (TestName, Description, Price, TestType)
VALUES 
('Complete Blood Count', 'A comprehensive blood test to evaluate overall health and detect a variety of disorders.', 45.00, 'Blood Test'),
('Lipid Panel', 'Measures cholesterol levels and triglycerides to assess heart disease risk.', 30.00, 'Blood Test'),
('Basic Metabolic Panel', 'Tests for glucose, calcium, and electrolytes to assess metabolic health.', 40.00, 'Blood Test'),
('Urinalysis', 'A test of urine to check for signs of kidney disease or diabetes.', 25.00, 'Urine Test'),
('Thyroid Function Test', 'Measures thyroid hormones to evaluate thyroid gland function.', 50.00, 'Blood Test'),
('X-Ray', 'Radiographic imaging to view the inside of the body.', 100.00, 'Imaging Test'),
('MRI Scan', 'Magnetic resonance imaging for detailed images of organs and tissues.', 500.00, 'Imaging Test'),
('COVID-19 PCR Test', 'A diagnostic test to detect active COVID-19 infection.', 75.00, 'Molecular Test');

INSERT INTO Insurances (InsuranceName, InsuranceAmount, InsuranceDetails, InsuranceStatus)
VALUES 
('HealthPlus Insurance', 100000.00, 'Covers general health services including hospital stays, outpatient care, and preventive services.', 'Active'),
('FamilyCare Insurance', 150000.00, 'Comprehensive coverage for families including pediatric care, maternity services, and wellness check-ups.', 'Active'),
('Senior Health Insurance', 80000.00, 'Designed for seniors with coverage for chronic disease management and specialized geriatric care.', 'Active'),
('Basic Health Insurance', 50000.00, 'Basic coverage for emergency services and essential health care.', 'Active'),
('Elite Medical Insurance', 200000.00, 'Premium coverage with extensive benefits including international travel coverage and concierge services.', 'Active'),
('Preventive Care Insurance', 60000.00, 'Focuses on preventive services like vaccinations and annual check-ups.', 'Active');

INSERT INTO `Accounts` (`AccountID`, `Username`, `Password`, `Role`, `AccountStatus`, `IV`) VALUES
(1, 'Tommer', 0x01a7502b9b69f70d929a1b113fb60b5c5281960fda3c7ea56fc144cf7b67455450cd9a524835e7a57881095d42589942e97bfe4992e4e010cb234512e9233b4f, 'Patient', 'active', 0x2ded00b9ec0de1376a3d4847fe2fc3fc),
(2, 'Nathan', 0x4a23ae527b2a28b52cd7d6492db473210c6f0ef643be662eb3dcee86646daeded1ec99068dbbeb47cbac2ee0484fb49d5f53411d40a6af3c310f29d74192990f, 'Secretary', 'active', 0x5e8da309506bebe335aefb19815ef1d3),
(3, 'Secretary', 0xb5045caad05c37997a022353ae8a60db3ab01e55784d83cdbf4825befae2db3193045b3445f054f9f308338180c9e9f2fe03c3f332fdafbcccaa9b653e4c5170, 'Secretary', 'active', 0x6cd32ce171b9873a5373a31b33139ac8),
(4, 'Physician', 0x0da5dcc13b1f9978bd9c9502c96b1d05b5f99e787197d2290fea408477c94afcddb13455c1b785a72568293aa609ac315665ffae85831d12bd635ee98dfd7bef, 'LabStaff', 'active', 0x49f831572c42a94980595f1530051368),
(5, 'Pathologist', 0xc62e9c9fcf775401ceb6ac18cf1caa369c3d97f598572e1730ba2648fd2d0f560ba9b0323583eef6aae15278639505628694da03a3b2244d3543ab1dc3652a7e, 'LabStaff', 'active', 0x595fb044d62916f1cb7c88b13a977086),
(6, 'Tim', 0x91f58420bdf4d371be97f5654bb6aa0813b8cc3c2788881eba4864b1f3359e3bded18470a2a0d135e14365767e8238a45577169c6714b171a62249f73ea70d31, 'Patient', 'active', 0x25040ed1ac110eff1e5d4995e95607b8),
(7, 'Vincent', 0x0f3161d07fff19e2a972bb8af7b026d884d60d2ce728ff67eb76225187e84ea1463fc6324ecc0f3775145e91a938426e92508a38f4e1aea5bf89a68f0e4d28ae, 'Patient', 'active', 0x3f164d4f0a8a50c6ce00c051fc280f61);

INSERT INTO `Patients` (`PatientID`, `AccountID`, `FirstName`, `LastName`, `DateOfBirth`, `Gender`, `Phone`, `Email`) VALUES
(1, 1, 'Tommer', 'Ching', '2024-11-20', 'Male', 0x1515c7fa6cd66a743eed95b21063f956, 0xc54db5f880e094bf1a08ff551438c5d35a3921ce2349af3ca2e949066ade6a44);

INSERT INTO `Secretaries` (`SecretaryID`, `AccountID`, `FirstName`, `LastName`, `DateOfBirth`, `Gender`, `Phone`, `Email`) VALUES
(1, 2, 'Nathan', 'T', '2024-11-20', 'Male', 0x6c60da2da25f9b2088df199c3abd5af6, 0xf2f44d9e168c7dc94e587fa54f917268),
(2, 3, 'Chris', 'Wong', '2024-11-05', 'Male', 0x97b206db9b75aa6b50565cc700a946ff, 0x384a52a755810c9b7ac940be92c0bab4);

INSERT INTO `LabStaffs` (`LabStaffID`, `AccountID`, `LabStaffType`, `FirstName`, `LastName`, `DateOfBirth`, `Gender`, `Phone`, `Email`) VALUES
(1, 4, 'Physician', 'Marco', 'Yue', '2024-11-20', 'Male', 0xf4642db6c30e0aaed6932b4e4be87bb1, 0x8c7e84af65ceba6ce84187f2c6c04f0a),
(2, 5, 'Pathologist', 'Tai Man', 'Chan', '2024-11-11', 'Male', 0xf9377095ebee03417bc172f7c833d682, 0xaef4b7667ee741c9b0923540f9d88d78),
(3, 6, 'Physician', 'Tim', 'kwan', '2024-11-19', 'Male', 0x181fff29282f2d3fe13430f511365ef4, 0x0919c213faaaf14555dc21490b9a6783),
(4, 7, 'Pathologist', 'Vincent', 'Wong', '2024-11-20', 'Male', 0xa4b1e12e8ef8286cfe78a15c16d7b497, 0x4f40cdb18f7c6dd14683bf1a3c4c6b9fc5f3662097fdbe2178a9de7568f89cb3);
