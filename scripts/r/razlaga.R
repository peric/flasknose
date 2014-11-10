
#RAZLAGA POSAMEZNE NAPOVEDI
explainInstance <- function(my.model, my.data, my.instance, m=200)
# my.model    |  regresijski model nau�en na podatkih
# my.data     |  u�ni podatki
# my.instance |  primer, ki ga �elimo razlo�iti
# m           |  �tevilo vzorcev
# predpostavimo da:
# - my.data ima N stolpcev, prvih N-1 so atributi, zadnji je regresijska spremenljivka
# atribut je bodisi numeri�en med 0 in 1 ali faktor s poljubno mnogo vrednostmi
# podani model ima pripadajo�o predict() funkcijo
# ! my.data potrebujemo, da dolo�imo zalogo vrednosti atributov
{
  nFeatures <- length(my.data[1,])-1
  explanation <- array(0, dim = nFeatures)
  for (i in 1:nFeatures) #za vsak atribut
  {

    for (j in 1:m) #vzamemo m vzorcev
    {
      perm <- sample(1:nFeatures,nFeatures,replace=F)    #izberemo nakljucno permutacijo
      temp.instance1 <- my.instance
      idx = 1
      while (perm[idx] != i) # v temp.instance1 na random postavimo vrednosti atributov pred i v permutaciji (ekvivalentno bi bilo "za i v permutaciji")
      {
        temp.instance1[perm[idx]] <- selectRandomValue(my.data[,perm[idx]]) 
        idx = idx + 1
      }
 
      temp.instance2 <- temp.instance1
      temp.instance2[perm[idx]] <- selectRandomValue(my.data[,perm[idx]]) # temp.instance2 se od temp.instance1 razlikuje v tem, da pri njem naklju�no izberemo tudi vrednost i-tega atributa
      explanation[i] = explanation[i] + predict(my.model, newdata = temp.instance1) - predict(my.model, newdata = temp.instance2) # izra�unamo en vzorec in ga pri�tejemo

    }
    explanation[i] = explanation[i] / m;  # povpre�imo
  }
  
  # rezultat: vektor prispevkov atributov za ta primer, po eden za vsak atribut | primer, ki smo ga razlagali | napoved modela za ta primer
  return (list(explanation = explanation, instance = my.instance, prediction = predict(my.model, newdata = my.instance))) 
}




#RAZLAGA CELEGA MODELA
explainValue <- function(my.model, my.data, featureIdx, value, m)
# my.model    |  regresijski model nau�en na podatkih
# my.data     |  u�ni podatki
# featureIdx  |  zaporedna �tevilka atributa, za katerega vrednost bomo ra�unali prispevek
# value       |  vrednost atributa, za katero ra�unamo prispevek
# m           |  �tevilo vzorcev
# za dodatna pojaslila glej �e explainInstance!
{
  nFeatures <- length(my.data[1,])-1
  psi <- array(0, dim = m)

  for (j in 1:m) #vzamemo m vzorcev
  {
    temp.instance1 <- my.data[1,]
    temp.instance1[featureIdx] <- value
    
    for (i in 1:nFeatures)
      if (i != featureIdx) temp.instance1[i] <- selectRandomValue(my.data[,i]) # 1: vsi naklju�no, razen i-tega
  
    temp.instance2 <- temp.instance1
    temp.instance2[featureIdx] <- selectRandomValue(my.data[,featureIdx])  # 2: vsi naklju�no
    psi[j] = predict(my.model, newdata = temp.instance1) - predict(my.model, newdata = temp.instance2)
  }
  return (list(psi = mean(psi), stdev = sd(psi)))  # rezultat: povpre�en prispevek vrednosti | standardna deviacija vzorcev
}



selectRandomValue <- function(feature)
{
  if (class(feature) == "numeric") return (sample(round((10^4) * min(feature),0):round((10^4) * max(feature), 0), 1) / (10^4))
  if (class(feature) == "factor") return (factor((levels(feature)[sample(1:length(levels(feature)), 1)]), levels=levels(feature)))
  print("Feature not numeric nor factor")
  return (0)
}


explainDiscreteAtr <- function(regression.model.1, training.data, attribute.no, samples=200)
{
	#stevilo vrednosti diskretnega atributa
	podatki <- training.data[,attribute.no]
	vrednosti <- nlevels(podatki)

	# vrednosti atributa
	# x <-  as.numeric(levels(podatki))    #as.numeric(levels(podatki))[podatki]
	x <- as.numeric(levels(podatki))[podatki]
	prispevki <- vector()
	deviacije <- vector()

	for (i in 1:vrednosti)                
	{
		res.1 <- explainValue(regression.model.1, training.data, attribute.no, factor(x[i], levels=levels(as.factor(x))), samples)
		prispevki[i] <- res.1$psi
		deviacije[i] <- res.1$stdev
	}
	list(x=x, prispevki=prispevki, deviacije=deviacije)
}




explainContAtr <- function(regression.model, training.data, attribute.no, resolution=50, samples=250)
{
	x <- vector()
	prispevki <- vector()
	deviacije <- vector()
	for (i in 1:resolution)
	{
		res.1 <- explainValue(regression.model, training.data, attribute.no, i / resolution, samples)
		x[i] <- i / resolution
		prispevki[i] <- res.1$psi
		deviacije[i] <- res.1$stdev
		cat(i, "/", resolution, " ", sep=""); if(i %% 10 == 0) cat("\n"); flush.console()
	}
	list(x=x, prispevki=prispevki, deviacije=deviacije)
}


