USE [master]
GO
/****** Object:  Database [CESAV]    Script Date: 08/08/2022 10:07:46 ******/
CREATE DATABASE [CESAV]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'CESAV', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\CESAV.mdf' , SIZE = 73728KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'CESAV_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\CESAV_log.ldf' , SIZE = 73728KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT
GO
ALTER DATABASE [CESAV] SET COMPATIBILITY_LEVEL = 150
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [CESAV].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [CESAV] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [CESAV] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [CESAV] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [CESAV] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [CESAV] SET ARITHABORT OFF 
GO
ALTER DATABASE [CESAV] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [CESAV] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [CESAV] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [CESAV] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [CESAV] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [CESAV] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [CESAV] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [CESAV] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [CESAV] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [CESAV] SET  DISABLE_BROKER 
GO
ALTER DATABASE [CESAV] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [CESAV] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [CESAV] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [CESAV] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [CESAV] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [CESAV] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [CESAV] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [CESAV] SET RECOVERY FULL 
GO
ALTER DATABASE [CESAV] SET  MULTI_USER 
GO
ALTER DATABASE [CESAV] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [CESAV] SET DB_CHAINING OFF 
GO
ALTER DATABASE [CESAV] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [CESAV] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [CESAV] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [CESAV] SET ACCELERATED_DATABASE_RECOVERY = OFF  
GO
EXEC sys.sp_db_vardecimal_storage_format N'CESAV', N'ON'
GO
ALTER DATABASE [CESAV] SET QUERY_STORE = OFF
GO
USE [CESAV]
GO
/****** Object:  User [laravel]    Script Date: 08/08/2022 10:07:46 ******/
CREATE USER [laravel] FOR LOGIN [laravel] WITH DEFAULT_SCHEMA=[dbo]
GO
ALTER ROLE [db_owner] ADD MEMBER [laravel]
GO
ALTER ROLE [db_accessadmin] ADD MEMBER [laravel]
GO
ALTER ROLE [db_securityadmin] ADD MEMBER [laravel]
GO
ALTER ROLE [db_ddladmin] ADD MEMBER [laravel]
GO
ALTER ROLE [db_backupoperator] ADD MEMBER [laravel]
GO
ALTER ROLE [db_datareader] ADD MEMBER [laravel]
GO
ALTER ROLE [db_datawriter] ADD MEMBER [laravel]
GO
ALTER ROLE [db_denydatareader] ADD MEMBER [laravel]
GO
ALTER ROLE [db_denydatawriter] ADD MEMBER [laravel]
GO
/****** Object:  Table [dbo].[FileLoadStatus]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[FileLoadStatus](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[FileName] [varchar](100) NULL,
	[LoadDateTime] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[migrations]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[migrations](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[migration] [nvarchar](255) NOT NULL,
	[batch] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[personal_access_tokens](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[tokenable_type] [nvarchar](255) NOT NULL,
	[tokenable_id] [bigint] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[token] [nvarchar](64) NOT NULL,
	[abilities] [nvarchar](max) NULL,
	[last_used_at] [datetime] NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TAB_CONTRATOS]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_CONTRATOS](
	[co_unidade] [smallint] NOT NULL,
	[nu_produto] [smallint] NOT NULL,
	[nu_contrato] [varchar](15) NOT NULL,
	[valor_base] [decimal](19, 2) NOT NULL,
	[data_arquivo] [date] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TAB_PRODUTOS]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_PRODUTOS](
	[nu_produto] [smallint] NOT NULL,
	[no_produto] [varchar](20) NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TAB_TEMP_CONTRATOS]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_TEMP_CONTRATOS](
	[co_unidade] [smallint] NOT NULL,
	[nu_produto] [smallint] NOT NULL,
	[qtd] [int] NULL,
	[valor_base] [decimal](19, 2) NULL,
	[data_arquivo] [date] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TAB_UNIDADES]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_UNIDADES](
	[co_unidade] [smallint] NOT NULL,
	[no_unidade] [varchar](20) NOT NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_token_unique]    Script Date: 08/08/2022 10:07:46 ******/
CREATE UNIQUE NONCLUSTERED INDEX [personal_access_tokens_token_unique] ON [dbo].[personal_access_tokens]
(
	[token] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_tokenable_type_tokenable_id_index]    Script Date: 08/08/2022 10:07:46 ******/
CREATE NONCLUSTERED INDEX [personal_access_tokens_tokenable_type_tokenable_id_index] ON [dbo].[personal_access_tokens]
(
	[tokenable_type] ASC,
	[tokenable_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
ALTER TABLE [dbo].[FileLoadStatus] ADD  DEFAULT (getdate()) FOR [LoadDateTime]
GO
/****** Object:  StoredProcedure [dbo].[ObtemContratos]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[ObtemContratos]
    @DataInicial DATE,   
    @DataFinal DATE,
	@nu_produto smallint,
	@co_unidade smallint
AS   
    SET NOCOUNT ON;  
    SELECT * 
    FROM [dbo].[TAB_CONTRATOS]
    WHERE co_unidade = @co_unidade
    AND nu_produto = @nu_produto
	AND data_arquivo BETWEEN @DataInicial AND @DataFinal;  
GO
/****** Object:  StoredProcedure [dbo].[ObtemUltimaPosicao]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[ObtemUltimaPosicao]

AS   
    SET NOCOUNT ON;  
    SELECT  DISTINCT nu_contrato, nu_produto, co_unidade, valor_base, data_arquivo
    FROM [dbo].[TAB_CONTRATOS]
	WHERE data_arquivo = (SELECT CONVERT(date,max(LoadDateTime)) as [yyyy-mm-dd] from FileLoadStatus)
 
GO
/****** Object:  StoredProcedure [dbo].[QtdContratosPorData]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[QtdContratosPorData]
    @data_arquivo DATE,   
	@qtd smallint OUTPUT
AS   
    SET NOCOUNT ON;  
    SELECT @qtd = COUNT(nu_contrato) 
    FROM [dbo].[TAB_CONTRATOS]
    WHERE data_arquivo = @data_arquivo

	SELECT @qtd AS qtd
GO
/****** Object:  StoredProcedure [dbo].[SinteticoPorUnidade]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[SinteticoPorUnidade]
    @data_inicio DATE,
	@data_final DATE,
	@unidade int,
	@produto int,
	@soma DECIMAL(19,2) OUTPUT,
	@qtd int OUTPUT
AS   
    SET NOCOUNT ON;  

	truncate table TAB_TEMP_CONTRATOS

	WHILE @data_inicio <= @data_final

	BEGIN

	SELECT @soma = SUM(valor_base) 
	FROM [dbo].[TAB_CONTRATOS]
	WHERE data_arquivo = @data_inicio 
	AND co_unidade = @unidade
	AND nu_produto = @produto

    SELECT @qtd = COUNT(nu_contrato) 
    FROM [dbo].[TAB_CONTRATOS]
    WHERE data_arquivo = @data_inicio
	AND co_unidade = @unidade
	AND nu_produto = @produto
	

	INSERT INTO [TAB_TEMP_CONTRATOS] (co_unidade, nu_produto, qtd, valor_base, data_arquivo) VALUES (@unidade, @produto, @qtd ,@soma, @data_inicio)

	SET @data_inicio = DATEADD(DAY,1,@data_inicio);
	END

	SELECT * FROM [TAB_TEMP_CONTRATOS]
	
	
	


GO
/****** Object:  StoredProcedure [dbo].[SinteticoTodos]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[SinteticoTodos]
    @data_inicio DATE,
	@data_final DATE

AS   
    SET NOCOUNT ON;  

	BEGIN

	SELECT co_unidade, nu_produto,data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
	FROM [TAB_CONTRATOS]
	WHERE data_arquivo BETWEEN @data_inicio and @data_final
	GROUP BY co_unidade, nu_produto,data_arquivo
	ORDER BY data_arquivo

	END
	
	
	


GO
/****** Object:  StoredProcedure [dbo].[ValorTotalPorData]    Script Date: 08/08/2022 10:07:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[ValorTotalPorData]
    @data_arquivo DATE,
	@soma DECIMAL(19,2) OUTPUT
AS   
    SET NOCOUNT ON;  
    SELECT @soma = SUM(valor_base) 
    FROM [dbo].[TAB_CONTRATOS]
    WHERE data_arquivo = @data_arquivo

	SELECT @soma AS total

GO
USE [master]
GO
ALTER DATABASE [CESAV] SET  READ_WRITE 
GO
