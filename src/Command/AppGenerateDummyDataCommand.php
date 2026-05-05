<?php

namespace App\Command;

use App\Entity\Banner;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\ContactMessage;
use App\Entity\Product;
use App\Entity\ProductFaq;
use App\Entity\ProductImage;
use App\Entity\ProductReview;
use App\Entity\Representative;
use App\Entity\Showroom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[AsCommand(name: 'app:generate-dummy-data', description: 'Gera dados fictícios para o Grupo Rocie.')]
class AppGenerateDummyDataCommand extends Command
{
    private AsciiSlugger $slugger;

    public function __construct(
        private EntityManagerInterface $em,
        private ParameterBagInterface $params
    ) {
        parent::__construct();
        $this->slugger = new AsciiSlugger('pt');
    }

    protected function configure(): void
    {
        $this->addOption('clean', null, InputOption::VALUE_NONE, 'Limpar dados antes de gerar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Gerando dados fictícios — Grupo Rocie');

        foreach (['/uploads/product', '/uploads/category', '/uploads/brand', '/uploads/showroom', '/uploads/banner'] as $dir) {
            $path = $this->params->get('kernel.project_dir') . '/public' . $dir;
            if (!is_dir($path)) mkdir($path, 0777, true);
        }

        if ($input->getOption('clean')) {
            $io->info('Limpando dados antigos...');
            foreach ([ProductReview::class, ProductFaq::class, ProductImage::class, ContactMessage::class, Product::class, Category::class, Brand::class, Showroom::class, Representative::class, Banner::class] as $cls) {
                $this->em->createQuery("DELETE FROM $cls e")->execute();
            }
        }

        // --- CATEGORIAS ---
        $categoriesData = [
            ['Verão', 'verao', 'Produtos para o verão'],
            ['Decoração', 'decoracao', 'Itens decorativos para casa'],
            ['Natal', 'natal', 'Decorações e presentes de Natal'],
            ['Papelaria', 'papelaria', 'Material de papelaria e escritório'],
            ['Utilidades', 'utilidades', 'Utilidades domésticas'],
            ['Limpeza', 'limpeza', 'Produtos de limpeza e organização'],
            ['Mochilas', 'mochilas', 'Mochilas e bolsas'],
            ['Eletrônicos', 'eletronicos', 'Acessórios eletrônicos'],
            ['Inverno', 'inverno', 'Produtos para o inverno'],
            ['Cama', 'cama', 'Cama, mesa e banho'],
        ];
        $categories = [];
        foreach ($categoriesData as [$name, $slug, $desc]) {
            $cat = new Category();
            $cat->setName($name)->setSlug($slug)->setShortDescription($desc)
                ->setActive(true)->setShowOnHome(true)->setSortOrder(array_search([$name,$slug,$desc], $categoriesData));
            $this->em->persist($cat);
            $categories[$slug] = $cat;
        }

        // --- MARCAS ---
        $brandsData = ['Grupo Rocie', 'Winth', 'Fix', 'Fiorella Sotti', 'WAB'];
        $brands = [];
        foreach ($brandsData as $i => $name) {
            $slug = strtolower($this->slugger->slug($name));
            $brand = new Brand();
            $brand->setName($name)->setSlug($slug)->setActive(true)->setShowOnHome(true)->setSortOrder($i);
            $this->em->persist($brand);
            $brands[] = $brand;
        }

        // --- PRODUTOS REAIS ---
        $productsData = [
            // Verão
            ['Jogo de Bilhas com copo de Shot', 'verao', 'ELJ0215', 'Jogo de Bilhar Sinuca com copos de Shot'],
            ['Kit para Exercício Funcional', 'verao', 'ELC07024', 'Kit completo para exercícios funcionais'],
            ['Kit de Mergulho e Natação', 'verao', 'ELP1061', 'Kit completo para natação e mergulho'],
            ['Jogo Raquete de Frescobol', 'verao', 'ELJ0482', 'Jogo de tênis e frescobol'],
            ['Munhequeira com protetor palmar', 'verao', 'ELC0601', 'Munhequeira de proteção'],
            ['Lança Água', 'verao', 'ELB15004', 'Brinquedo lança água para verão'],
            ['Colchão Inflável Cacto', 'verao', 'ELP03042', 'Colchão inflável divertido'],
            ['Piscina Inflável', 'verao', 'ELP01001', 'Piscina inflável para família'],
            // Decoração
            ['Espada de Samurai Decorativa', 'decoracao', 'DEB01042', 'Decoração temática japonesa'],
            ['Barquinho Decorativo de Madeira', 'decoracao', 'DED02067', 'Barquinho artesanal em madeira'],
            ['Nossa Senhora Aparecida Decorativa', 'decoracao', 'DED05101', 'Imagem decorativa de Nossa Senhora'],
            ['Placa Decorativa', 'decoracao', 'DTG05035', 'Placa decorativa variada'],
            ['Mesa de Canto MDF', 'decoracao', 'DTJ0152', 'Mesa de canto em MDF'],
            ['Castiçal de Vidro Cilíndrico', 'decoracao', 'DED01020', 'Castiçal elegante em vidro'],
            ['Capa de Almofada Geométrica', 'decoracao', 'DTE01048', 'Capa de almofada com estampa geométrica'],
            ['Espelho Decorativo', 'decoracao', 'DEQ01120', 'Espelho decorativo para parede'],
            // Natal
            ['Enfeites de Tecido Sortidos', 'natal', 'NTD10006', 'Enfeites natalinos de tecido coloridos'],
            ['Bandeja com Alça Papai Noel', 'natal', 'NTW13802', 'Bandeja decorativa de Natal'],
            ['Árvore de Natal com Fibra Ótica', 'natal', 'NTX8150', 'Árvore de Natal iluminada com fibra ótica'],
            ['Farol Decorativo', 'natal', 'NTD91028', 'Farol decorativo para Natal'],
            ['Presépio de Resina', 'natal', 'NTD2069', 'Presépio natalino em resina'],
            ['Trio de Papai Noel Equilibrista', 'natal', 'NTD1038', 'Trio decorativo de Papai Noel'],
            ['Saia de Árvore de Natal', 'natal', 'NTD11043', 'Saia decorativa para árvore de Natal'],
            ['Placa Decorativa HO HO HO', 'natal', 'NTD10029', 'Placa de madeira natalina'],
            // Papelaria
            ['Agenda com Frases', 'papelaria', 'PPA52145', 'Agenda anual com frases motivacionais'],
            ['Borracha Escolar Colorida', 'papelaria', 'WPB20096', 'Borracha colorida para escola'],
            ['Caneta Tinta Gel Glitter', 'papelaria', 'WPD30002', 'Caneta esferográfica glitter'],
            ['Fita Adesiva Colorida', 'papelaria', 'WPF20016', 'Fita adesiva em cores variadas'],
            ['Globo Terrestre Metalizado', 'papelaria', 'WPB00004', 'Globo terrestre decorativo metalizado'],
            ['Lápis de Cor 24 Cores', 'papelaria', 'WPD10057', 'Caixa com 24 lápis de cor'],
            ['Massinha de E.V.A Colorida', 'papelaria', 'WPA50016', 'Massinha de modelar colorida'],
            ['Planner Espiral', 'papelaria', 'WPM01019', 'Planner organizador espiral'],
            // Utilidades
            ['Bomboniere', 'utilidades', 'VDA07035', 'Bomboniere decorativa'],
            ['Caneca Cappuccino com Colher', 'utilidades', 'CEC01083', 'Caneca especial para cappuccino'],
            ['Coqueteleira Shaker 500ml', 'utilidades', 'ELG65010', 'Coqueteleira profissional 500ml'],
            ['Jarro de Vidro', 'utilidades', 'VDA05016', 'Jarro de vidro para mesa'],
            ['Espremedor de Frutas', 'utilidades', 'CLA08186', 'Espremedor manual de frutas'],
            ['Jogo Americano', 'utilidades', 'CLA0521', 'Jogo americano para mesa'],
            ['Kit de Colheres Medidoras', 'utilidades', 'CLA03280', 'Kit colheres medidoras e pás'],
            ['Prato de Vidro para Bolo', 'utilidades', 'VDA0214', 'Prato de vidro para bolos'],
            // Limpeza
            ['Suporte e Escova Sanitária', 'limpeza', 'CLB03056', 'Kit suporte com escova sanitária'],
            ['Kit Escova e Pá de Lixo', 'limpeza', 'CLB03050', 'Kit escova e pá de lixo'],
            ['Escova de Limpeza para Garrafas', 'limpeza', 'CLB03052', 'Escova específica para garrafas'],
            ['Escova de Limpeza Multiuso', 'limpeza', 'CLB03058', 'Escova multiuso para limpeza'],
            ['Escova Flexível para Cantos', 'limpeza', 'CLB01047', 'Escova flexível para locais de difícil acesso'],
            ['Dispenser para Sabonete Líquido', 'limpeza', 'CLB03075', 'Dispenser elegante para sabonete'],
            ['Desentupidor Coletor de Cabelo', 'limpeza', 'CLB01049', 'Desentupidor especial para banheiro'],
            ['Vassoura Mágica', 'limpeza', 'CLB01051', 'Vassoura retrátil multiuso'],
            // Mochilas
            ['Mochila With Kids Smooth Cream', 'mochilas', 'BPD32853', 'Mochila infantil premium'],
            ['Mochila Fiorella Sotti Successful', 'mochilas', 'BPF30861', 'Mochila feminina executiva'],
            ['Mochila Youth Margaridas', 'mochilas', 'BPG32072', 'Mochila juvenil com estampa de margaridas'],
            ['Mochila Winth Teen Panda', 'mochilas', 'BPT30023', 'Mochila teen com estampa de panda'],
            ['Mochila Winth Basic Banana', 'mochilas', 'BPG31954', 'Mochila básica estampada'],
            ['Mochila Quiver com Camurça', 'mochilas', 'BPQ30071', 'Mochila premium com acabamento em camurça'],
            ['Mochila Winth Baby Térmica', 'mochilas', 'BPC31793', 'Mochila baby com bolso térmico'],
            ['Mochila Fiorella Sotti Metalizada', 'mochilas', 'BPF30921', 'Mochila feminina com acabamento metalizado'],
            // Eletrônicos
            ['Aspirador Automotivo Portátil', 'eletronicos', 'FXS01001', 'Aspirador portátil para carro'],
            ['Calculadora Digital', 'eletronicos', 'FXC1304', 'Calculadora digital compacta'],
            ['Campainha Musical sem Fio', 'eletronicos', 'FXA01007', 'Campainha wireless com sons variados'],
            ['Fone de Ouvido Intra-Auricular', 'eletronicos', 'FXF01004', 'Fone de ouvido ergonômico'],
            ['Lanterna Led', 'eletronicos', 'FXL19025', 'Lanterna LED de alta luminosidade'],
            ['Luz Noturna LED', 'eletronicos', 'FXN01033', 'Luz noturna com sensor automático'],
            ['Luminária de Mesa LED', 'eletronicos', 'FXG90036', 'Luminária LED para escritório'],
            ['Pilha AAA Alcalina', 'eletronicos', 'FXP2212', 'Pilha palito AAA de longa duração'],
            // Inverno
            ['Meia com Antiderrapante', 'inverno', 'FRL04100', 'Meia quentinha com sola antiderrapante'],
            ['Meia de Compressão', 'inverno', 'FRL04104', 'Meia de compressão terapêutica'],
            ['Meia Soquete', 'inverno', 'FRL04059', 'Meia soquete confortável'],
            ['Kit com 4 Pares de Luvas', 'inverno', 'FRL07012', 'Kit luvas variadas para o inverno'],
            ['Toca Gorro e Luva', 'inverno', 'FRL10019', 'Conjunto toca, gorro e luva'],
            ['Meia Térmica', 'inverno', 'FRL04098', 'Meia térmica para baixas temperaturas'],
            ['Kit com 2 Pantufas', 'inverno', 'FRL05041', 'Kit pantufas macias e quentes'],
            ['Kit com 2 Pares de Pantufa de Inverno', 'inverno', 'FRL05040', 'Pantufas de inverno premium'],
            // Cama
            ['Colcha Queen', 'cama', 'CLC926', 'Colcha queen size elegante'],
            ['Cobre Leito Solteiro', 'cama', 'CLB01128', 'Cobre leito para cama solteiro'],
            ['Cobre Leito Queen', 'cama', 'CLQ02216', 'Cobre leito para cama queen'],
            ['Edredom Casal', 'cama', 'ELC12196', 'Edredom casal confortável'],
            ['Edredom Queen', 'cama', 'ELQ02066', 'Edredom queen size premium'],
            ['Jogo de Cama Queen', 'cama', 'JCQ03049', 'Jogo de cama queen completo'],
            ['Jogo de Cama King', 'cama', 'JKI02023', 'Jogo de cama king size'],
            ['Jogo de Cama Casal', 'cama', 'JCC21141', 'Jogo de cama casal completo'],
            ['Lençol de Baixo Casal', 'cama', 'JCJ02359', 'Lençol com elástico casal'],
            ['Travesseiro', 'cama', 'ELV03270', 'Travesseiro de alta qualidade'],
        ];

        $slugsUsed = [];
        foreach ($productsData as [$name, $catSlug, $code, $desc]) {
            $baseSlug = strtolower($this->slugger->slug($name));
            $slug = $baseSlug;
            $i = 1;
            while (in_array($slug, $slugsUsed)) {
                $slug = $baseSlug . '-' . $i++;
            }
            $slugsUsed[] = $slug;

            $aboutLines = [
                "Material resistente e de alta qualidade",
                "Design ergonômico e funcional",
                "Fácil de limpar e manter",
                "Produto 100% testado e aprovado pelo controle de qualidade",
                "Ideal para uso doméstico e profissional",
                "Acompanha instruções de uso detalhadas",
            ];
            shuffle($aboutLines);
            $aboutItems = implode("\n", array_slice($aboutLines, 0, rand(4, 6)));

            $ratingAvg = number_format(rand(38, 50) / 10, 1);
            $ratingCount = rand(12, 2847);

            $product = new Product();
            $product->setName($name)
                ->setSlug($slug)
                ->setInternalCode($code)
                ->setSku($code)
                ->setShortDescription($desc)
                ->setAboutItems($aboutItems)
                ->setFullDescription("<p>$desc.</p><p>Produto de alta qualidade do Grupo Rocie, com acabamento premium e durabilidade garantida. Fabricado com materiais selecionados para oferecer o melhor desempenho e longa vida útil.</p>")
                ->setBenefits("<ul><li>Qualidade superior comprovada</li><li>Durabilidade garantida</li><li>Design moderno e funcional</li></ul>")
                ->setMaterial(['Plástico ABS', 'Aço Inox', 'Polipropileno', 'Nylon reforçado', 'Alumínio'][rand(0,4)])
                ->setWeight(rand(100, 1500) . 'g')
                ->setDimensions(rand(10,40) . 'x' . rand(5,30) . 'x' . rand(3,15) . ' cm')
                ->setWarranty('12 meses contra defeito de fabricação')
                ->setOrigin('Brasil')
                ->setRatingAverage($ratingAvg)
                ->setRatingCount($ratingCount)
                ->setMainCategory($categories[$catSlug] ?? null)
                ->setBrand($brands[array_rand($brands)])
                ->setActive(true)
                ->setIsFeatured(rand(0, 4) === 0)
                ->setIsNew(rand(0, 3) === 0)
                ->setSortOrder(rand(1, 100))
                ->setMainImage($this->downloadImage('prod_'));

            // FAQs
            $faqData = [
                ['Qual é o prazo de garantia?', 'O produto possui 12 meses de garantia contra defeitos de fabricação.'],
                ['Qual o material utilizado?', 'O produto é fabricado com materiais de alta qualidade, resistentes e duráveis.'],
                ['Como realizar a limpeza?', 'Limpe com pano úmido e detergente neutro. Evite produtos abrasivos.'],
                ['O produto é certificado pelo INMETRO?', 'Sim, todos os nossos produtos seguem as normas de segurança brasileiras.'],
            ];
            foreach ($faqData as $i => [$q, $a]) {
                $faq = new ProductFaq();
                $faq->setQuestion($q)->setAnswer($a)->setSortOrder($i)->setActive(true)->setProduct($product);
                $this->em->persist($faq);
            }

            // Reviews
            $reviewAuthors = [
                ['Maria S.', 'São Paulo, SP'], ['João P.', 'Rio de Janeiro, RJ'],
                ['Ana C.', 'Belo Horizonte, MG'], ['Carlos M.', 'Curitiba, PR'],
                ['Fernanda L.', 'Porto Alegre, RS'], ['Roberto A.', 'Salvador, BA'],
            ];
            $reviewTexts = [
                ['Produto excelente!', 'Chegou rápido e a qualidade superou minhas expectativas. Recomendo muito!'],
                ['Ótimo custo-benefício', 'Produto de boa qualidade pelo preço. Cumpre bem o que promete.'],
                ['Muito satisfeita', 'Material resistente e bonito. Já estou usando e adorei.'],
                ['Recomendo!', 'Comprei para presente e a pessoa adorou. Produto de qualidade.'],
                ['Superou as expectativas', 'Produto chegou antes do prazo, bem embalado e em perfeito estado.'],
                ['Bom produto', 'Qualidade ok para o preço. Entrega rápida.'],
            ];
            $numReviews = rand(2, 5);
            for ($r = 0; $r < $numReviews; $r++) {
                [$aName, $aLoc] = $reviewAuthors[$r % count($reviewAuthors)];
                [$rTitle, $rBody] = $reviewTexts[$r % count($reviewTexts)];
                $daysAgo = rand(5, 365);
                $review = new ProductReview();
                $review->setAuthorName($aName)
                    ->setAuthorLocation($aLoc)
                    ->setRating(rand(3, 5))
                    ->setTitle($rTitle)
                    ->setBody($rBody)
                    ->setVerified(rand(0,1) === 1)
                    ->setActive(true)
                    ->setReviewedAt(new \DateTimeImmutable("-{$daysAgo} days"))
                    ->setProduct($product);
                $this->em->persist($review);
            }

            // Gallery images (3-5 per product)
            $numImages = rand(3, 5);
            for ($imgIdx = 0; $imgIdx < $numImages; $imgIdx++) {
                $filename = $this->downloadImage('prod_', 600, 600);
                if ($filename) {
                    $pi = new ProductImage();
                    $pi->setImage($filename)
                       ->setAltText($name . ' — imagem ' . ($imgIdx + 1))
                       ->setIsMain($imgIdx === 0)
                       ->setSortOrder($imgIdx)
                       ->setActive(true)
                       ->setProduct($product);
                    $this->em->persist($pi);
                }
            }

            $this->em->persist($product);
        }

        // --- SHOWROOMS ---
        $showroomsData = [
            ['Rio de Janeiro', 'RJ', 'Centro', 'Av. Rio Branco', '100', '20040-001', '(21) 3333-4444'],
            ['São Paulo', 'SP', 'Moema', 'Av. Ibirapuera', '2907', '04029-200', '(11) 3333-4444'],
            ['Porto Alegre', 'RS', 'Moinhos de Vento', 'Rua Padre Chagas', '100', '90570-080', '(51) 3333-4444'],
            ['Recife', 'PE', 'Boa Viagem', 'Av. Boa Viagem', '3500', '51020-000', '(81) 3333-4444'],
        ];
        foreach ($showroomsData as $i => [$city, $state, $neighborhood, $address, $number, $zip, $phone]) {
            $showroom = new Showroom();
            $showroom->setName("Showroom $city")
                ->setCity($city)->setState($state)
                ->setNeighborhood($neighborhood)->setAddress($address)
                ->setNumber($number)->setZipcode($zip)->setPhone($phone)
                ->setWhatsapp(preg_replace('/\D/', '', $phone))
                ->setOpeningHours('Seg-Sex: 9h-18h | Sáb: 9h-13h')
                ->setActive(true)->setSortOrder($i)
                ->setMainImage($this->downloadImage('showroom_', 800, 500));
            $this->em->persist($showroom);
        }

        // --- REPRESENTANTES ---
        $states = ['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'];
        foreach ($states as $i => $state) {
            $rep = new Representative();
            $rep->setName("Representante $state")->setState($state)
                ->setPhone('(11) 9' . rand(7000, 9999) . '-' . rand(1000, 9999))
                ->setWhatsapp('11' . rand(70000000, 99999999))
                ->setEmail("rep.$state@gruporocie.com.br")
                ->setActive(true);
            $this->em->persist($rep);
        }

        // --- BANNERS ---
        $bannersData = [
            ['Produtos que sua loja precisa', 'Variedade, marcas e atendimento para lojistas de todo o Brasil', 'Veja os Produtos'],
            ['Conheça nosso Showroom', 'Visite nossos espaços nas principais capitais do Brasil', 'Ver Showrooms'],
            ['Seja um Representante', 'Faça parte da nossa rede de representantes comerciais', 'Saiba Mais'],
        ];
        foreach ($bannersData as $i => [$title, $subtitle, $btnText]) {
            $banner = new Banner();
            $banner->setTitle($title)->setSubtitle($subtitle)
                ->setButtonText($btnText)->setButtonUrl('/produtos')
                ->setDisplayPage('home')->setSortOrder($i)->setActive(true)
                ->setDesktopImage($this->downloadImage('banner_', 1280, 720));
            $this->em->persist($banner);
        }

        // --- MENSAGENS ---
        $names = ['João Silva', 'Maria Souza', 'Carlos Oliveira', 'Ana Lima', 'Pedro Fernandes'];
        $subjects = ['Quero conhecer os produtos', 'Quero ser representante', 'Visitar showroom', 'SAC', 'Outros'];
        for ($i = 0; $i < 5; $i++) {
            $msg = new ContactMessage();
            $msg->setName($names[$i])
                ->setEmail('contato' . $i . '@example.com')
                ->setPhone('(11) 99999-000' . $i)
                ->setSubject($subjects[$i])
                ->setMessage('Olá! Gostaria de mais informações sobre os produtos do Grupo Rocie.')
                ->setStatus($i === 0 ? 'nova' : 'respondida')
                ->setFormType('contact');
            $this->em->persist($msg);
        }

        $this->em->flush();
        $io->success('Dados fictícios gerados com sucesso! ' . count($productsData) . ' produtos, ' . count($showroomsData) . ' showrooms, 27 representantes, ' . count($bannersData) . ' banners.');
        return Command::SUCCESS;
    }

    private function downloadImage(string $prefix, int $w = 600, int $h = 400): string
    {
        $mapping = ['prod_' => 'product', 'showroom_' => 'showroom', 'banner_' => 'banner', 'cat_' => 'category', 'brand_' => 'brand'];
        $dir = $mapping[$prefix] ?? 'product';
        $filename = $prefix . uniqid() . '.jpg';
        $path = $this->params->get('kernel.project_dir') . "/public/uploads/$dir/$filename";
        $url = "https://picsum.photos/seed/" . uniqid() . "/{$w}/{$h}";
        try {
            $content = @file_get_contents($url);
            if ($content !== false) { file_put_contents($path, $content); return $filename; }
        } catch (\Exception $e) {}
        return '';
    }
}
