<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SegmentNasabahTest extends TestCase
{
    public function test_setSegmentNasabah_success()
    {
        $url = "https://dev.multindo.co.id/MultindoMobile/zrestdev/newmitra/Mitradealer/getNotification";
        // Fake respons dari API eksternal
        Http::fake([
            $url => Http::response([
                "status_code" => 1,
                "status_desc" => "Sukses",
                "counts" => 2,
                "results" => [
                    [
                        "notifid" => "1",
                        "target" => "1",
                        "title" => "ini title",
                        "body" => "ini boyd",
                        "actions" => "1",
                        "type" => "LOCAL",
                        "app" => "ex",
                        "stsread" => 1,
                        "createdate" => null,
                        "createuser" => null,
                        "dealerid" => "xxxxx",
                        "userid" => "081575875860"
                    ]
                ]
            ], 200),
        ]);

        // Data yang akan dikirim sebagai POST request
        $postData = [
            "keypaket" => "63465768769674583",
            "namapaket" => "id.co.multindo.sismafmobile.ex",
            "user" => "161992299",
            "token" => "cNhTcR7LSaWEmf-W9O6r8a:APA91bGrzdXzjkjLO2iSjcTWeHMzYwdAnjcQKj9j8cl9es_IMp-9xN0Sw4lFDJWAlpCXuNJKSEBnKyZol2yjXs-scoF_l87MTzrBHuhsps8Zj8YLQCLIQhflpz7_M_Br4-l_5mWloNVi",
            "dealerid" => "xxxxx"
        ];

        // Lakukan POST request ke API
        $response = Http::post($url, $postData);

        // Lakukan pengujian terhadap respons
        $this->assertEquals(200, $response->status());

        // Ambil JSON respons sebagai array
        $responseData = json_decode($response->getBody(), true);

        // Lakukan pengujian terhadap struktur JSON respons
        $this->assertArrayHasKey("status_code", $responseData);
        $this->assertArrayHasKey("status_desc", $responseData);
        $this->assertArrayHasKey("counts", $responseData);
        $this->assertArrayHasKey("results", $responseData);
        $this->assertIsArray($responseData["results"]);
        $this->assertCount(1, $responseData["results"]); // Sesuaikan dengan jumlah yang diharapkan
        $this->assertArrayHasKey("notifid", $responseData["results"][0]);
        $this->assertArrayHasKey("target", $responseData["results"][0]);
        $this->assertArrayHasKey("title", $responseData["results"][0]);
        $this->assertArrayHasKey("body", $responseData["results"][0]);
    }

    public function test_setSegmentNasabah_failed()
    {
        $url = "https://dev.multindo.co.id/MultindoMobile/zrestdev/newmitra/Mitradealer/getNotification";
        // Fake respons dari API eksternal
        Http::fake([
            $url => Http::response(['status_code' => 8], 200),
        ]);

        // Data yang akan dikirim sebagai POST request
        $postData = [
            "keypaket" => "63465768769674583",
            "namapaket" => "id.co.multindo.sismafmobile.ex",
            "user" => "161992299",
            "token" => "dI2sdxY4TAe9jctaKJaeeG:APA91bF5dGMawK-aHORiViwDwToIz1-2WU-bP4woVRjoKFVou0kE66REKnhiDdul-7ziEa-B2H9_YK8OLG5xpFXsnN1TVTT7jPiWByeezi4OsaMZBLxu0NNWnRt8GpIKvEC706gJ-Ion",
            "dealerid" => "xxxxx"
        ];

        // Lakukan POST request ke API
        $response = Http::post($url, $postData);

        // Lakukan pengujian terhadap respons
        $this->assertEquals(200, $response->status());
        $this->assertEquals(8, $response->json('status_code'));
    }
}
