<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding a Dish");
        $dishs = [
        [
            ['name' => 'Lươn đùm - bánh tráng chiên'],
            [
                'dish_group_id' => 1,
                'description' => 'Lươn đùm lá chuối, ăn kèm bánh tráng chiên giòn'
            ]
        ],
        [
            ['name' => 'Bao tử phá lấu - ram chả - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Bao tử, phá lấu, ram chả chiên giòn, bánh phồng tôm'
            ]
        ],
        [
            ['name' => 'Gỏi hải sản tươi - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Tôm, mực luộc, hành tây, rau răm, đậu phộng rang, bánh phồng tôm'
            ]
        ],
        [
            ['name' => 'Gỏi bò tái chanh'],
            [
                'dish_group_id' => 1,
                'description' => 'Thịt bò, hành tây, rau thơm, ớt hiểm, đậu phộng rang giã nhỏ'
            ]
        ],
        [
            ['name' => 'Gỏi củ hủ dừa tôm thịt - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Củ hủ dừa giòn ngọt trộn tôm thẻ, thịt ba chỉ, hành phi, bánh phồng tôm'
            ]
        ],
        [
            ['name' => 'Gỏi bò bóp thấu - ram chả - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Búp bò, ram chả chiên, bánh phồng tôm, nước mắm chua ngọt'
            ]
        ],
        [
            ['name' => 'Gỏi Thái - ram chả'],
            [
                'dish_group_id' => 1,
                'description' => 'Tôm, thịt heo, chả trộn kiểu Thái cay cay chua ngọt, kèm ram chả chiên giòn'
            ]
        ],
        [
            ['name' => 'Xà lách trộn bò'],
            [
                'dish_group_id' => 1,
                'description' => 'Xà lách, cà chua, dưa leo, thịt bò thái lát,cà chua sốt dầu giấm hoặc mayonnaise'
            ]
        ],
        [
            ['name' => 'Xà lách trộn cá hộp'],
            [
                'dish_group_id' => 1,
                'description' => 'Xà lách tươi giòn, cà chua bi, trứng cút, cá hộp trộn sốt mayonnaise hoặc dầu giấm'
            ]
        ],
        [
            ['name' => 'Gỏi rau càng cua - ram chả - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Rau càng cua giòn tan trộn tôm thịt, ram chả, đậu phộng, hành phi, bánh phồng tôm'
            ]
        ],
        [
            ['name' => 'Gỏi gân bò trộn - BPT'],
            [
                'dish_group_id' => 1,
                'description' => 'Gân bò giòn sần sật trộn , hành tây, rau thơm, bánh phồng tôm, nước mắm chua ngọt'
            ]
        ],
        [
            ['name' => 'Chân gà rút xương'],
            [
                'dish_group_id' => 1,
                'description' => 'Chân gà ta rút xương ngâm sả tắc chua ngọt hoặc trộn kiểu Thái'
            ]
        ],
        [
            ['name' => 'Gỏi hoa chuối - ram chả'],
            [
                'dish_group_id' => 1,
                'description' => 'Hoa chuối bào mỏng trộn tôm thịt, ram chả chiên giòn, đậu phộng rang'
            ]
        ],
        [
            ['name' => 'Kim chi thịt nướng - ram chả, nem'],
            [
                'dish_group_id' => 1,
                'description' => 'Thịt ba chỉ nướng cuốn kim châm, ram chả, nem nướng, rau sống, bánh tráng'
            ]
        ],
        [
            ['name' => 'Súp tôm chả'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp sánh mịn với tôm tươi và chả cua/chả tôm, cà rốt, nấm tuyết,bắp mỹ'
            ]
        ],
        [
            ['name' => 'Súp thập cẩm'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp tổng hợp các loại thịt, hải sản và rau củ theo mùa'
            ]
        ],
        [
            ['name' => 'Súp tôm cua'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp truyền thống với thịt cua tươi, tôm và trứng cút'
            ]
        ],
        [
            ['name' => 'Súp tôm bắp non'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp ngọt thanh với tôm, bắp non và nấm hương'
            ]
        ],
        [
            ['name' => 'Súp tóc tiên'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp đặc biệt với tóc tiên , nấm, trứng và các loại hải sản'
            ]
        ],
        [
            ['name' => 'Súp gà măng tay'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp gà xé phay, măng tây và nấm đông cô'
            ]
        ],
        [
            ['name' => 'Súp hoành thánh'],
            [
                'dish_group_id' => 2,
                'description' => 'Hoành thánh nhân thịt tôm, nấm hương trong nước dùng nóng hổi'
            ]
        ],
        [
            ['name' => 'Súp cua gà'],
            [
                'dish_group_id' => 2,
                'description' => 'Súp kết hợp thịt cua, thịt gà xé và trứng cút'
            ]
        ],
        [
            ['name' => 'Gà hấp xôi chiên'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà hấp lá chanh, ăn kèm xôi nếp chiên giòn'
            ]
        ],
        [
            ['name' => 'Gà bó xôi'],
            [
                'dish_group_id' => 3,
                'description' => 'Thịt gà ta nguyên con bó trong lớp xôi chiên vàng giòn'
            ]
        ],
        [
            ['name' => 'Gà chiên mắm, xôi phồng'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà chiên giòn rưới nước mắm tỏi ớt, ăn kèm xôi nếp chiên phồng'
            ]
        ],
        [
            ['name' => 'Gà rô ty bánh bao chiên'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà rô-ti đậm đà, ăn kèm bánh bao chiên giòn'
            ]
        ],
        [
            ['name' => 'Gà lên mâm xôi đậu'],
            [
                'dish_group_id' => 3,
                'description' => 'Thịt gà xé trộn, lòng gà, xôi đậu xanh và rau thơm trên mâm'
            ]
        ],
        [
            ['name' => 'Gà lên mâm xôi hạt sen'],
            [
                'dish_group_id' => 3,
                'description' => 'Thịt gà, lòng gà và xôi hạt sen trên mâm, ăn kèm nước chấm gừng'
            ]
        ],
        [
            ['name' => 'Gà quay - bánh bao chiên'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà quay da giòn, thịt mềm, ăn kèm bánh bao chiên'
            ]
        ],
        [
            ['name' => 'Gà lên mâm 2 món'],
            [
                'dish_group_id' => 3,
                'description' => 'Thịt gà được chế biến thành hai món (ví dụ: gỏi gà và gà luộc) kèm xôi'
            ]
        ],
        [
            ['name' => 'Đùi gà rô ty - bánh bao chiên'],
            [
                'dish_group_id' => 3,
                'description' => 'Đùi gà rô-ti thấm vị, ăn kèm bánh bao chiên'
            ]
        ],
        [
            ['name' => 'Gà hấp lá chanh'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà ta hấp cùng lá chanh thơm lừng, thịt dai ngọt'
            ]
        ],
        [
            ['name' => 'Gà nướng lá chanh'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà nướng than hồng với lá chanh, da giòn thơm'
            ]
        ],
        [
            ['name' => 'Gà nướng muối ớt'],
            [
                'dish_group_id' => 3,
                'description' => 'Gà nướng cay cay vị muối ớt, da vàng giòn'
            ]
        ],
        [
            ['name' => 'Vịt quay Bắc Kinh'],
            [
                'dish_group_id' => 4,
                'description' => 'Vịt quay da giòn, thịt mềm, ăn kèm bánh tráng mỏng, hành tây và nước sốt đặc trưng'
            ]
        ],
        [
            ['name' => 'Bò nhúng ớt'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò Mỹ tươi thái mỏng nhúng lẩu ớt hiểm cay nồng'
            ]
        ],
        [
            ['name' => 'Bò nhúng giấm'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò nhúng giấm chua ngọt, ăn kèm nước mắm chua ngọt, bánh tráng và rau sống'
            ]
        ],
        [
            ['name' => 'Bò thui - Bê non xào xả ớt'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò thui tái, bê non xào xả ớt đậm đà'
            ]
        ],
        [
            ['name' => 'Bò bê non cuộn'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò/bê non cuốn nấm kim châm hoặc rau củ nướng'
            ]
        ],
        [
            ['name' => 'Bò bê non hấp mướp'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò/bê non hấp cùng mướp hương, nóng hổi, thanh mát'
            ]
        ],
        [
            ['name' => 'Bò cuốn cải'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò nướng cuốn trong lá cải xanh, chấm mắm nêm'
            ]
        ],
        [
            ['name' => 'Bò bít tết - khoai tây chiên'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò bít tết áp chảo, sốt tiêu đen/nấm, ăn kèm khoai tây chiên'
            ]
        ],
        [
            ['name' => 'Bò lúc lắc - khoai tây chiên'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò cắt vuông xào hành tây, ớt chuông, ăn kèm khoai tây chiên'
            ]
        ],
        [
            ['name' => 'Bò xào bơ xanh'],
            [
                'dish_group_id' => 5,
                'description' => 'Thịt bò xào bơ tỏi, bông cải xanh và các loại rau củ'
            ]
        ],
        [
            ['name' => 'Dê hấp cung đình'],
            [
                'dish_group_id' => 6,
                'description' => 'Thịt dê tơ hấp sả gừng, ăn kèm rau thơm, chấm chao'
            ]
        ],
        [
            ['name' => 'Dê um nén'],
            [
                'dish_group_id' => 6,
                'description' => 'Thịt dê um với củ nén, sả và ớt, ăn kèm bánh mì hoặc bún'
            ]
        ],
        [
            ['name' => 'Mực hấp gừng'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực tươi hấp với gừng, hành lá, giữ được độ giòn ngọt tự nhiên'
            ]
        ],
        [
            ['name' => 'Mực chiên xù'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực tươi tẩm bột chiên xù, giòn rụm bên ngoài, mềm bên trong'
            ]
        ],
        [
            ['name' => 'Mực tuơi nướng'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực tươi nướng than hồng hoặc nướng sa tế'
            ]
        ],
        [
            ['name' => 'Mực um sa tế'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực tươi um sa tế, cay nồng, đậm đà'
            ]
        ],
        [
            ['name' => 'Mực nhúng ớt'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực tươi nhúng lẩu ớt hiểm cay tê, độc đáo'
            ]
        ],
        [
            ['name' => 'Mực độn '],
            [
                'dish_group_id' => 7,
                'description' => 'Mực ống dồn thịt băm, nấm mèo và bún tàu, hấp hoặc chiên'
            ]
        ],
        [
            ['name' => 'Mực chiên mắm'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực chiên giòn, xốc với nước mắm tỏi ớt đường'
            ]
        ],
        [
            ['name' => 'Mực cơm chiên mắm'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực cơm nhỏ chiên giòn, xốc với nước mắm đậm vị'
            ]
        ],
        [
            ['name' => 'Mực cơm hấp sốt thái'],
            [
                'dish_group_id' => 7,
                'description' => 'Mực cơm hấp, chấm với sốt Thái chua cay'
            ]
        ],
        [
            ['name' => 'Tôm chiên hoả tiển'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm cuộn khoai tây chiên giòn, hình dáng như tên gọi'
            ]
        ],
        [
            ['name' => 'Tôm càng xanh hấp xả'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm càng xanh hấp cùng sả cây, giữ độ ngọt tự nhiên'
            ]
        ],
        [
            ['name' => 'Tôm chiên cốm xanh'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm sú tẩm cốm xanh chiên, lớp ngoài giòn, thơm mùi cốm'
            ]
        ],
        [
            ['name' => 'Tôm sú hấp xả'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm sú tươi hấp sả, ăn kèm muối tiêu chanh'
            ]
        ],
        [
            ['name' => 'Tôm nướng xiên'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm tươi nướng trên xiên, tẩm ướp gia vị vừa ăn'
            ]
        ],
        [
            ['name' => 'Tôm đất rang muối'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm đất nhỏ rang muối ớt, vỏ giòn, thịt ngọt'
            ]
        ],
        [
            ['name' => 'Tôm chiên xù'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm sú tẩm bột chiên xù, vàng giòn'
            ]
        ],
        [
            ['name' => 'Tôm chiên giòn'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm tươi chiên nhanh, giòn cả vỏ'
            ]
        ],
        [
            ['name' => 'Tôm rim me'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm sú rim với sốt me chua ngọt đậm đà'
            ]
        ],
        [
            ['name' => 'Tôm cuộn bò bía'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm tươi cuộn trong bánh bò bía, chiên giòn'
            ]
        ],
        [
            ['name' => 'Tôm hữu'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm thẻ/sú rim nước dừa, mặn ngọt vừa ăn'
            ]
        ],
        [
            ['name' => 'Tôm rang xả'],
            [
                'dish_group_id' => 8,
                'description' => 'Tôm rang cùng sả băm, cay thơm'
            ]
        ],
        [
            ['name' => 'Bò kho - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Bò kho tiêu, cà rốt, khoai tây, ăn kèm bánh mì giòn'
            ]
        ],
        [
            ['name' => 'Bò hầm tiêu xanh - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt bò hầm mềm với tiêu xanh nguyên hạt, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Gân bò hầm - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Gân bò hầm mềm, béo ngậy, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Giò hầm bác trân - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Giò heo hầm thuốc bắc, bổ dưỡng, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Cari bò largu - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt bò nấu cà ri kiểu Largu, đậm đà nước cốt dừa, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Cari sườn nấu nấm - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Sườn heo nấu cà ri với các loại nấm, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Cari cốt lết - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Cốt lết heo nấu cà ri, béo thơm, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Sườn non um chuối - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Sườn non um chuối xanh, đậu phụ, đậm vị, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Vịt hầm táo - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Vịt hầm táo đỏ, bổ dưỡng, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Vịt nấu đậu - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt vịt nấu đậu phụ, đậm đà, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Vịt nấu nấm - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt vịt nấu các loại nấm tươi, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Vịt hầm giả cầy - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt vịt hầm theo kiểu giả cầy (ướp riềng mẻ), thơm ngon, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Vịt tiềm - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt vịt tiềm thuốc bắc, thơm phức, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Gà tiềm hạt sen - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt gà ta tiềm hạt sen, bổ dưỡng, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Gà hầm củ sọ - bánh mì'],
            [
                'dish_group_id' => 9,
                'description' => 'Thịt gà hầm với củ sọ (khoai sọ/môn), béo bùi, ăn kèm bánh mì'
            ]
        ],
        [
            ['name' => 'Lẫu cá bớp - Bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu cá bớp tươi, nước lẩu chua cay, ăn kèm bún tươi và rau'
            ]
        ],
        [
            ['name' => 'Lẫu hải sản - bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu thập cẩm tôm, mực, nghêu, ăn kèm bún tươi và rau'
            ]
        ],
        [
            ['name' => 'Lẫu bò - Bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu thịt bò, gân bò, với nước lẩu đậm đà, ăn kèm bún tươi'
            ]
        ],
        [
            ['name' => 'Lẫu nấm gà - Bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu gà nấu cùng nhiều loại nấm, nước lẩu ngọt thanh, ăn kèm bún tươi'
            ]
        ],
        [
            ['name' => 'Lẫu gà lá giang - Bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu gà với lá giang chua nhẹ, dân dã, ăn kèm bún tươi'
            ]
        ],
        [
            ['name' => 'Lẫu cá cu - Bún tươi'],
            [
                'dish_group_id' => 9,
                'description' => 'Lẩu cá cu tươi, nước lẩu chua cay, ăn kèm bún tươi'
            ]
        ],
        [
            ['name' => 'Cơm dương châu'],
            [
                'dish_group_id' => 9,
                'description' => 'Cơm chiên với lạp xưởng, trứng, tôm, thịt xá xíu, cà rốt, hạt đậu'
            ]
        ],
        [
            ['name' => 'Cơm chiên hải sản'],
            [
                'dish_group_id' => 9,
                'description' => 'Cơm chiên với tôm, mực, cà rốt, hạt đậu, trứng'
            ]
        ],
        [
            ['name' => 'Cơm chiên trứng'],
            [
                'dish_group_id' => 9,
                'description' => 'Cơm chiên đơn giản với trứng, hành lá, gia vị'
            ]
        ],
        [
            ['name' => 'Cá diêu hồng chiên xù'],
            [
                'dish_group_id' => 10,
                'description' => 'Cá diêu hồng nguyên con chiên giòn, ăn kèm nước mắm gừng hoặc sốt me'
            ]
        ],
        [
            ['name' => 'Nghêu hấp xả'],
            [
                'dish_group_id' => 10,
                'description' => 'Nghêu hấp cùng sả, ớt, nước dừa/nước dùng'
            ]
        ],
        [
            ['name' => 'Chíp chíp hấp xả'],
            [
                'dish_group_id' => 10,
                'description' => 'Chíp chíp (nghêu nhỏ) hấp sả, ăn kèm muối tiêu chanh'
            ]
        ],
        [
            ['name' => 'Bánh hỏi thịt nướng'],
            [
                'dish_group_id' => 10,
                'description' => 'Bánh hỏi rắc mỡ hành, ăn kèm thịt heo nướng và rau sống'
            ]
        ],
        [
            ['name' => 'Heo quay bánh hỏi'],
            [
                'dish_group_id' => 10,
                'description' => 'Thịt heo quay da giòn, ăn kèm bánh hỏi rắc mỡ hành và rau'
            ]
        ],
        [
            ['name' => 'Heo quay bánh chưng'],
            [
                'dish_group_id' => 10,
                'description' => 'Thịt heo quay da giòn, ăn kèm bánh chưng chiên'
            ]
        ],
        [
            ['name' => 'Bánh bao bánh vạc'],
            [
                'dish_group_id' => 10,
                'description' => 'Bánh bột lọc nhân tôm, trong suốt, mềm dẻo, rắc hành phi, ăn kèm nước chấm chua ngọt. '
            ]
        ],
        [
            ['name' => 'Cá mú hấp xì dầu'],
            [
                'dish_group_id' => 10,
                'description' => 'Cá mú tươi sống hấp với xì dầu, gừng, hành lá'
            ]
        ],
        [
            ['name' => 'Cá mú hấp thập cẩm'],
            [
                'dish_group_id' => 10,
                'description' => 'Cá mú hấp cùng nấm, thịt băm, bún tàu và các loại rau củ'
            ]
        ],
        [
            ['name' => 'Cá cu hấp'],
            [
                'dish_group_id' => 10,
                'description' => 'Cá cu hấp gừng hoặc hấp xì dầu'
            ]
        ],
        [
            ['name' => 'Cá chim hấp'],
            [
                'dish_group_id' => 10,
                'description' => 'Cá chim hấp với nấm mèo, bún tàu hoặc hấp gừng'
            ]
        ],
        [
            ['name' => 'Bao tử phá lấu'],
            [
                'dish_group_id' => 10,
                'description' => 'Bao tử heo phá lấu, nấu cùng ngũ vị hương, nước cốt dừa'
            ]
        ],
        [
            ['name' => 'Bao tử hầm tiêu'],
            [
                'dish_group_id' => 10,
                'description' => 'Bao tử heo hầm mềm với tiêu xanh, đậm đà'
            ]
        ],
        [
            ['name' => 'Kem plan'],
            [
                'dish_group_id' => 11,
                'description' => 'Kem flan trứng sữa caramel mềm mịn'
            ]
        ],
        [
            ['name' => 'Sữa chua'],
            [
                'dish_group_id' => 11,
                'description' => 'Sữa chua dẻo hoặc sữa chua truyền thống, ăn kèm trái cây/trân châu'
            ]
        ],
        [
            ['name' => 'Đông sương'],
            [
                'dish_group_id' => 11,
                'description' => 'Thạch rau câu, thường ăn kèm nước cốt dừa hoặc cà phê'
            ]
        ],
        [
            ['name' => 'Trái cây'],
            [
                'dish_group_id' => 11,
                'description' => 'Trái cây tươi theo mùa, dùng làm món tráng miệng'
            ]
        ],
    ];

        foreach ($dishs as $dish) {
            Dish::updateOrCreate(...$dish);
        }

        $this->command->info("finish seeding a Dish");
    }
}
