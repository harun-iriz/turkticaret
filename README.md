Turkticaret PHP Case Kurulum

Harun IRIZ

Backend repository: https://github.com/harun-iriz/turkticaret.git


1-	Clone repository 

2-	Terminalde cd ile dosya dizinine gidin.

3-	“composer install” komutunu çalıştırın.

4-	.env.example dosyasını yeniden adlandırın veya .env olarak kopyalayın.

5-	“php artisan key:generate” komutunu çalıştırın.

6-	.env dosyanızda DB_DATABASE=turkticaret olarak ayarlayın.

7-	XAMPP veya Wamp uygulamaları üzerinden Apache ve MySQL serverlarını başlatın.

8-	Google üzerinden http://localhost:8080/phpmyadmin sayfasına gidin. (! Port numaranıza dikkat ediniz.)

9-	turkticaret adında yeni bir veritabanı oluşturun.

10-	“php artisan migrate” komutunu çalıştırın.

11-	“php artisan db:seed” komutunu çalıştırın.

12-	“php artisan queue:work” komutunu çalıştırın ve kapatmayın.

13-	“php artisan serve” komutunu yeni bir terminalde çalıştırın ve kapatmayın.

14-	Postman uygulamasını açın ve clonelamış olduğunuz turkticaret dosyasının içinde bulunan kurulumDosyalari klasörünün içindeki “TurkTicaret.postman_collection.json” dosyasını import edin. (Eğer port numaranız farklı ise url kısmından düzenleyin.)

15-	Login requestini çalıştırın ve dönen results içindeki token’ı kopyalayın. 

16-	Create Order resquestine giderek Authorization kısmından Type’ı Bearer Token olarak değiştirin. Sağdaki gerekli kısma kopyalamış olduğunuz token’ı yapıştırın. Create Order requestini çalıştırabilirsiniz. 

17-	Oluşturmuş olduğunuz requestte dönen order_id’yi kopyalayarak Order Details requstinde url’in sonun yapıştırın. Authorization kısmından Type’ı Bearer Token olarak değiştirin. Sağdaki gerekli kısma kopyalamış olduğunuz token’ı yapıştırın.  Send işlemi gerçekleştirebilirsiniz.

Endpointler:

	(POST) login : Login işlemi için gerekli endpoint.
Body : 
{
    "email":"admin@admin.com",
    "password":"123456"
}

	(POST) login : Logout işlemi için gerekli endpoint.
Authorization: Bearer Token gerekli.

	(POST) order : Sipariş vermek için gerekli endpoint. Birden fazla ürün girilebilmektedir. “quantity” ile belirli üründen kaç adet sipariş verildiği bildirilir.
Body:
{
    "products":[
        {"product_id":31,"quantity":1},
        {"product_id":19,"quantity":1},
        {"product_id":111,"quantity":2}
    ]
}
Authorization: Bearer Token gerekli.

	(GET) order/{order_id} : Verilen sipariş hakkında bilgileri dönen endpoint. Ürünler, fatura ve yararlanılan kampanya.
Authorization: Bearer Token gerekli.

