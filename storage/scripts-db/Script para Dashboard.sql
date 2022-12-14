USE [CESAV]
GO
/****** Object:  Table [dbo].[FileLoadStatus]    Script Date: 09/08/2022 23:40:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[FileLoadStatus](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[FileName] [varchar](100) NULL,
	[LoadDateTime] [date] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[migrations]    Script Date: 09/08/2022 23:40:53 ******/
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
/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 09/08/2022 23:40:53 ******/
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
/****** Object:  Table [dbo].[TAB_CONTRATOS]    Script Date: 09/08/2022 23:40:53 ******/
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
/****** Object:  Table [dbo].[TAB_PRODUTOS]    Script Date: 09/08/2022 23:40:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_PRODUTOS](
	[nu_produto] [smallint] NOT NULL,
	[no_produto] [varchar](20) NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TAB_UNIDADES]    Script Date: 09/08/2022 23:40:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TAB_UNIDADES](
	[co_unidade] [smallint] NOT NULL,
	[no_unidade] [varchar](20) NOT NULL
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[FileLoadStatus] ADD  CONSTRAINT [DF__FileLoadS__LoadD__48CFD27E]  DEFAULT (getdate()) FOR [LoadDateTime]
GO
/****** Object:  StoredProcedure [dbo].[ObtemUltimaPosicao]    Script Date: 09/08/2022 23:40:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[ObtemUltimaPosicao]

AS   
    SET NOCOUNT ON;  

	/****** Retorna Posição Consolidada do Ultimo Arquivo ******/
		SELECT max(data_arquivo) as data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
		FROM [TAB_CONTRATOS]
		WHERE data_arquivo = (SELECT max(data_arquivo) from [TAB_CONTRATOS])

GO
/****** Object:  StoredProcedure [dbo].[SinteticoTodos]    Script Date: 09/08/2022 23:40:53 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[SinteticoTodos]
    @data_inicio DATE,
	@data_final DATE,
	@co_unidade smallint,
	@nu_produto smallint

AS   
    SET NOCOUNT ON;  

	/****** Retorna Tudo ******/
	IF(@co_unidade is null and @nu_produto is null)
	BEGIN
		SELECT co_unidade, nu_produto,data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
		FROM [TAB_CONTRATOS]
		WHERE data_arquivo BETWEEN @data_inicio and @data_final
		GROUP BY co_unidade, nu_produto,data_arquivo
		ORDER BY data_arquivo
	END
	/****** Retorna Todos as Unidades com Produto Especificado ******/
	ELSE IF(@co_unidade is null)
	BEGIN
		SELECT co_unidade, nu_produto,data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
		FROM [TAB_CONTRATOS]
		WHERE data_arquivo BETWEEN @data_inicio and @data_final
		and nu_produto = @nu_produto
		GROUP BY co_unidade, nu_produto,data_arquivo
		ORDER BY data_arquivo
	END
	/****** Retorna Todos os Produtos de uma Unidade Especificada ******/
	ELSE IF(@nu_produto is null)
	BEGIN
		SELECT co_unidade, nu_produto,data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
		FROM [TAB_CONTRATOS]
		WHERE data_arquivo BETWEEN @data_inicio and @data_final
		and co_unidade = @co_unidade
		GROUP BY co_unidade, nu_produto,data_arquivo
		ORDER BY data_arquivo
	END
	/****** Retorna Produto e Unidade especificados******/
	ELSE
	BEGIN
		SELECT co_unidade, nu_produto,data_arquivo,COUNT(nu_produto) as qtd, SUM(valor_base) as valor_base
		FROM [TAB_CONTRATOS]
		WHERE data_arquivo BETWEEN @data_inicio and @data_final
		and co_unidade = @co_unidade
		and nu_produto = @nu_produto
		GROUP BY co_unidade, nu_produto,data_arquivo
		ORDER BY data_arquivo
	END

	
	
	


GO
